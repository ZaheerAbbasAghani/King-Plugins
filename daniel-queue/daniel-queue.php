<?php

/*

Plugin Name: Daniel Queue

Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html

Description: Plugin allow to create salon on site and customers can request to salons for hair service

Version: 1.0

Author: Zaheer Abbas Aghani

Author URI: https://profiles.wordpress.org/zaheer01/

License: GPLv3 or later

Text Domain: daniel-queue

Domain Path: /languages

*/



defined("ABSPATH") or die("No direct access!");

class DanielQueue {



function __construct() {

	//define( 'WP_ALLOW_MULTISITE', true );

	add_action('init', array($this, 'dq_start_from_here'));

	add_action('wp_enqueue_scripts', array($this, 'dq_enqueue_script_front'));

	add_action("wp", array($this,"my_pmpro_confirmation_redirect"));
	register_activation_hook( __FILE__, array($this,'add_roles_on_plugin_activation'));
	

}



function add_roles_on_plugin_activation() {
       add_role( 'dq_customer', 'Customer', array( 'read' => true, 'level_0' => true ) );
   }




function dq_start_from_here() {

	require_once plugin_dir_path(__FILE__) . 'dq_front/dq_register_salon.php';

	require_once plugin_dir_path(__FILE__) . 'dq_inc/dq_create_salon_on_site.php';

	require_once plugin_dir_path(__FILE__) . 'dq_inc/dq_create_salon_dashboard.php';

	require_once plugin_dir_path(__FILE__) . 'dq_front/dq_show_all_salons.php';

	require_once plugin_dir_path(__FILE__) . 'dq_inc/dq_iam_in_queue.php';

	require_once plugin_dir_path(__FILE__) . 'dq_inc/dq_iam_out_of_queue.php';

	require_once plugin_dir_path(__FILE__) . 'dq_front/dq_queue.php';

}



// Enqueue Style and Scripts



function dq_enqueue_script_front() {

//Style & Script

wp_enqueue_style('dq-style', plugins_url('assets/css/dq.css', __FILE__),'1.0.0','all');

wp_enqueue_script('dq-script',plugins_url('assets/js/dq.js', __FILE__),array('jquery'),'1.0.0', true);



wp_enqueue_script('fd-validate', 'https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.min.js', array('jquery'), '',true);



wp_localize_script('dq-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));







}





function my_pmpro_confirmation_redirect()

{

	$confirmation_pages = array(1 => 12);	//change this use your membership level ids and page ids

	

	global $pmpro_pages;

	

	if(is_page($pmpro_pages['confirmation']))

	{

		foreach($confirmation_pages as $clevel => $cpage)

		{

			if(pmpro_hasMembershipLevel($clevel))

			{				

				wp_redirect(get_permalink($cpage));

				exit;

			}

		}

	}

}







} // class ends



// CHECK WETHER CLASS EXISTS OR NOT.



if (class_exists('DanielQueue')) {

$obj = new DanielQueue();

}