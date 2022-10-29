<?php 

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

$posts = get_posts('numberposts=-1&post_type=post&post_status=any' );

foreach ($posts as $post) {
	delete_post_meta( $post->ID, 'dp_day_of_year_number');
	unstick_post( $post->ID );
}

delete_option('dp_select_category');
delete_option('dp_select_tag');