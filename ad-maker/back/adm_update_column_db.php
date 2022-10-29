<?php 

if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1);

}
add_action( 'wp_ajax_adm_update_column_db', 'adm_update_column_db' );
add_action( 'wp_ajax_nopriv_adm_update_column_db', 'adm_update_column_db' );

function adm_update_column_db() {

global $wpdb; // this is how you get access to the database
$table_name1 = $wpdb->base_prefix.'adm_submitted_data_table';

$oldColumn = $_POST['oldValue'];
$newColumn = $_POST['newValue'];

$cname =  strtolower($oldColumn);
$column_name = str_replace(' ', '_',   preg_replace('/\(|\)/','',$cname));

$nname =  strtolower($newColumn);
$column_name2 = str_replace(' ', '_',   preg_replace('/\(|\)/','',$nname));

$row = $wpdb->get_results( "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
WHERE table_name = '$table_name1' AND column_name = '$column_name' " );


if(count($row) == 1){
        //echo "HELLO WORLD";
        $result = $wpdb->query("ALTER TABLE $table_name1 DROP COLUMN $column_name");
        $wpdb->query("ALTER TABLE $table_name1 ADD $column_name2 text NOT NULL");
}


wp_die();

}