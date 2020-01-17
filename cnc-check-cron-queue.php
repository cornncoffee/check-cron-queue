<?php

/* 
    Plugin Name: Corn & Coffee Check Cron Queue
    Plugin URI: 
    Description: Regularly checks how many cron jobs are overdue and keeps a log.
    Version: 0.1.0
    Author: Corn & Coffee
    Author URI: 
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

// adds new schedule
function cnc_add_cron_interval( $schedules ) {
    if( ! isset( $schedules['nearly_twice_daily'] )) {
        $schedules['nearly_twice_daily'] = array(
            'interval' => 43259,
            'display' => esc_html__( 'Nearly twice a day' ),
        );
    }
    return $schedules;
 }
add_filter( 'cron_schedules', 'cnc_add_cron_interval' );


add_action()
