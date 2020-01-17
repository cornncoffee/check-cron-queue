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

// global $wpdb;

function activate() {
    cnc_schedule_next_check();
}

function deactivate() {
    wp_clear_scheduled_hook( 'cnc_check_cron_queue' );
}

function cnc_schedule_next_check(){
    wp_schedule_single_event( time() + 43909, 'cnc_check_cron_queue' );
}

function cnc_check_cron_queue(){
    
    $cron_events = get_option( 'cron' );
    $overdue_events_history = get_option( 'cnc_overdue_events_history' );
    if( 50 < count( $overdue_events_history ) ){
        array_shift( $overdue_events_history );
    }

    $overdue_events = 0;
    foreach( $cron_events as $time => $event ){
        if( time() > $time ) $overdue_events++;
    }

    $overdue_events_history[time()] = $overdue_events;
    update_option( 'cnc_overdue_events_history', $overdue_events_history );

    cnc_schedule_next_check();
    
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