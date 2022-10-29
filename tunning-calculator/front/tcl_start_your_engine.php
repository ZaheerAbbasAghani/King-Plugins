<?php 

add_action( 'wp_ajax_tcl_start_your_engine', 'tcl_start_your_engine' );

add_action( 'wp_ajax_nopriv_tcl_start_your_engine', 'tcl_start_your_engine' );

function tcl_start_your_engine() {


global $wpdb;

$table_name = $wpdb->base_prefix.'tcl_tunning_calculator';

$make = $_POST['make'];
$model = $_POST['model'];
$trim = $_POST['trim'];

$query = "SELECT stock_power,stock_torque,stage_1_power,stage_1_torque,stage_2_power,stage_2_torque,stage_1_price,stage_2_price,stage1img,stage2img,learnmoreUrl FROM $table_name where make='$make' AND model='$model' AND trrim='$trim' ";
$results = $wpdb->get_results($query, ARRAY_A);

wp_send_json($results[0]);

wp_die();

}