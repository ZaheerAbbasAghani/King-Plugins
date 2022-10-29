<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Direct access restricted
global $wpdb;
$charset_collate = $wpdb->get_charset_collate();
$table_name = $wpdb->prefix . 'bmc_canvas_table';
if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		bmc_field varchar(10) NOT NULL,
		bmc_value varchar(255) NOT NULL,
		bmc_color varchar(15) NOT NULL,
		bmc_container varchar(50) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
}