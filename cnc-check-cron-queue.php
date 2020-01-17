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

public static function activate() {
    cnc_schedule_next_check();
}

public static function deactivate() {
    wp_clear_scheduled_hook( 'cnc_check_cron_queue' );
}

public static function cnc_schedule_next_check(){
    wp_schedule_single_event( time() + 43909, 'cnc_check_cron_queue' );
}

public static function cnc_check_cron_queue(){
    
    cnc_schedule_next_check();
}