<?php

/* 
    Plugin Name: Corn & Coffee - Check Cron Queue
    Plugin URI: 
    Description: Regularly checks how many cron jobs are overdue and keeps a history log.
    Version: 0.2.0
    Author: Corn & Coffee
    Author URI: 
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// PLUGIN SETTINGS -------------------------------------------------------------------------
// Note that multiplying the two numbers below will give you the time period in which the data was collected
if( ! defined( 'CNC_CCQ_INTERVAL_CHECK' ) ){
    define( 'CNC_CCQ_INTERVAL_CHECK', 2 ); // every how many hours the check is run
}
if( ! defined( 'CNC_CCQ_HISTORY_LENGTH' ) ){
    define( 'CNC_CCQ_HISTORY_LENGTH', 60 ); // how many readings should be kept in record
}

register_activation_hook( __FILE__, 'cnc_ccq_activation' );
register_deactivation_hook( __FILE__, 'cnc_ccq_deactivation' );
register_uninstall_hook( __FILE__, 'cnc_ccq_uninstall' );
add_action( 'wp_dashboard_setup', 'cnc_ccq_create_dashboard_widget' );
add_action( 'cnc_ccq_check_cron_queue', 'cnc_ccq_check_cron_queue' );

function cnc_ccq_activation(){
    cnc_ccq_schedule_next_check();
}

function cnc_ccq_deactivation(){
    wp_clear_scheduled_hook( 'cnc_ccq_check_cron_queue' );
}

function cnc_ccq_uninstall(){
    delete_option( 'cnc_ccq_overdue_events_history' );
    delete_option( 'cnc_ccq_current_info' );
}

function cnc_ccq_check_cron_queue(){
    
    $cron_events = get_option( 'cron' );
    $overdue_events_history = get_option( 'cnc_ccq_overdue_events_history' );
    if( $overdue_events_history && CNC_CCQ_HISTORY_LENGTH <= count( $overdue_events_history ) ){
        array_shift( $overdue_events_history );
    }

    $now = time();
    $overdue_events = 0;
    $longest_delay = 0;
    foreach( $cron_events as $event_time => $event_info ){
        if( 'version' !== $event_time && $now > $event_time ) {
            $overdue_events++;
            $longest_delay = max( $longest_delay, $now - $event_time );
        }
    }

    $overdue_events_history[$now] = array(
        'overdue_events' => $overdue_events,
        'longest_delay'  => $longest_delay,
    );
    update_option( 'cnc_ccq_overdue_events_history', $overdue_events_history );

    $highest_overdue    = 0;
    $total_overdue      = 0;
    $longest_delay      = 0;
    $records_no_overdue = 0;
    $records_total      = 0;
    $last_check         = null;

    foreach( $overdue_events_history as $record_time => $record_info ){

        $highest_overdue = max( $highest_overdue, $record_info['overdue_events'] ); // integer
        $total_overdue += $record_info['overdue_events']; // integer
        $longest_delay = max( $longest_delay, $record_info['longest_delay'] ); // integer (seconds)
        if( 0 === $record_info['overdue_events'] ) { $records_no_overdue++; } // integer
        $records_total++; // integer
        $last_check = max( $last_check, $record_time ); // time

    }

    if( 0 !== $records_total ){
        $average_overdue = round( $total_overdue / $records_total, 2 ); // decimal
        $percentage_no_overdue = ( 100 * round( $records_no_overdue / $records_total, 2 ) ) . '%'; // string (percentage)
    }
    $longest_delay = ( round( $longest_delay / 60, 2 ) ) . ' ' . __( 'minutes', 'cnc_check_cron_queue' ); // decimal
    $last_check = date( 'Y-m-d H:i:s', $last_check ); // string (datetime)

    $current_info = array(
        __( 'Highest number of events overdue:', 'cnc_check_cron_queue' )  => $highest_overdue,
        __( 'Average number of events overdue:', 'cnc_check_cron_queue' )  => $average_overdue,
        __( 'Longest delay of an overdue event:', 'cnc_check_cron_queue' ) => $longest_delay,
        __( 'Frequency of no events overdue:', 'cnc_check_cron_queue' )    => $percentage_no_overdue,
        __( 'Last check:', 'cnc_check_cron_queue' )                        => $last_check,
    );
    update_option( 'cnc_ccq_current_info', $current_info );

    cnc_ccq_schedule_next_check();
    
}

function cnc_ccq_schedule_next_check(){
    $next_run = time() + (CNC_CCQ_INTERVAL_CHECK*60*60) + rand(0, 1*60*60); // rand() is for adding some randomness and try to cover all hours and minutes of the day.
    wp_schedule_single_event( $next_run, 'cnc_ccq_check_cron_queue' );
}

function cnc_ccq_create_dashboard_widget(){
    wp_add_dashboard_widget( 'cnc_check_cron_queue', __( 'Info regarding your WP Cron events', 'cnc_check_cron_queue' ), 'cnc_ccq_dashboard' );
}

function cnc_ccq_dashboard(){

    $cnc_ccq_current_info = get_option( 'cnc_ccq_current_info' );

    if ( $cnc_ccq_current_info ){
        foreach( $cnc_ccq_current_info as $info_desc => $info_value ){
            echo( "<p>$info_desc <em>$info_value</em></p>" );
        }
    }else{
        echo( '<p>No data to display.</p>' );
    }

}