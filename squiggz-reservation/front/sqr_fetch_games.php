<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
@ini_set( 'display_errors', 1 );
}

add_action("wp_ajax_sqr_fetch_games", "sqr_fetch_games");
add_action("wp_ajax_nopriv_sqr_fetch_games", "sqr_fetch_games");

function sqr_fetch_games(){

	//print_r($_POST['post_id']);
	$post_id = $_POST['post_id'];
	$sqr_game_name 	= get_post_meta( $post_id, 'sqr_game_name', true);
    $seats_to_fill 	= get_post_meta( $post_id, 'seats_to_fill', true);
    $sqr_game_color = get_post_meta( $post_id, 'sqr_game_color', true);


	//unserialize($sqr_game_name);
	wp_send_json( array("games" => unserialize($sqr_game_name),"seats" => unserialize($seats_to_fill),"colors" => unserialize($sqr_game_color)) );

	wp_die();
}