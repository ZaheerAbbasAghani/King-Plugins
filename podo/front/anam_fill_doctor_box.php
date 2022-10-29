<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action( 'wp_ajax_nopriv_anam_fill_doctor_box', 'anam_fill_doctor_box' );
add_action( 'wp_ajax_anam_fill_doctor_box', 'anam_fill_doctor_box' );
function anam_fill_doctor_box() {
	

$blogusers = get_users(array('role__in'=>array('doctor')));
	echo '<option disabled selected> Select a doctor </option>';
foreach ( $blogusers as $user ) {
    echo '<option value="'.$user->id.'">'.esc_html($user->display_name).'</option>';
}

	wp_die();
}