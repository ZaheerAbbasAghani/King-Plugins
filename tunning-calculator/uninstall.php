<?php

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

global $wpdb;
$table_name = $wpdb->base_prefix.'tcl_tunning_calculator';
$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
delete_option("my_plugin_db_version");

delete_option( 'tcl_button_text' );
delete_option( 'tcl_gauge_background' );
delete_option( 'tcl_background' );
delete_option( 'tcl_background_fill' );

?>