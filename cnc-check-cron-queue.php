<?php

/* 
    Plugin Name: Corn & Coffee Check Cron Queue
    Plugin URI: 
    Description: Regularly checks how many cron jobs are overdue and keeps a history log.
    Version: 0.1.0
    Author: Corn & Coffee
    Author URI: 
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function activate() {
    cnc_ccq_schedule_next_check();
}

function deactivate() {
    wp_clear_scheduled_hook( 'cnc_ccq_check_cron_queue' );
}

function cnc_ccq_schedule_next_check(){
    $next_run = time() + (12*60*60) + rand(0, 1*60*60); // anything between 12 and 13 hours from now
    wp_schedule_single_event( $next_run, 'cnc_ccq_check_cron_queue' );
}

function cnc_ccq_check_cron_queue(){
    
    $cron_events = get_option( 'cron' );
    $overdue_events_history = get_option( 'cnc_ccq_overdue_events_history' );
    if( 50 < count( $overdue_events_history ) ){
        array_shift( $overdue_events_history );
    }

    $now = time();
    $overdue_events = 0;
    $longest_delay = 0;
    foreach( $cron_events as $event_time => $event_info ){
        if( $now > $event_time ) {
            $overdue_events++;
            $longest_delay = max( $longest_delay, $now - $event_time );
        }
    }

    $overdue_events_history[$now] = array(
        'overdue_events' => $overdue_events,
        'longest_delay'  => $longest_delay,
    );
    update_option( 'cnc_ccq_overdue_events_history', $overdue_events_history );

    cnc_ccq_schedule_next_check();
    
}

function cnc_ccq_create_dashboard_widget(){
    wp_add_dashboard_widget( 'cnc_check_cron_queue', __('Info regarding your WP Cron events'), 'cnc_ccq_dashboard' );
}
add_action( 'wp_dashboard_setup', 'cnc_ccq_create_dashboard_widget' );

function cnc_ccq_dashboard(){

    $overdue_events_history = get_option( 'cnc_ccq_overdue_events_history' );

    $highest_overdue = 0;
    $total_overdue = 0;
    $longest_delay = 0;
    $records_no_overdue = 0;
    $records_total = 0;
    $last_check = null;

    foreach( $overdue_events_history as $record_time => $record_info ){

        $highest_overdue = max( $highest_overdue, $record_info['overdue_events'] ); // integer
        $total_overdue += $record_info['overdue_events']; // integer
        $longest_delay = max( $longest_delay, $record_info['longest_delay'] ); // integer (seconds)
        if( 0 === $record_info['overdue_events'] ) { $records_no_overdue++ }; // integer
        $records_total++; // integer
        $last_check = max( $last_check, $record_time ); // time

    }

    $average_overdue = round( $total_overdue / $records_total, 2 ); // decimal
    $percentage_no_overdue = ( 100 * round( $records_no_overdue / $records_total, 2 ) ) . '%'; // string (percentage)

    echo( "<p>Highest number of overdue events: $highest_overdue</p>" );


}


// formato que ele devolve:
// Array (
//     [1579306599] => Array ( --------------------- esse deve ser o horário
//         [wp_privacy_delete_old_export_files] => Array (
//             [40cd750bba9870f18aada2478b24840a] => Array (
//                 [schedule] => hourly
//                 [args] => Array ( )
//                 [interval] => 3600
//             )
//         )
//     )
// )