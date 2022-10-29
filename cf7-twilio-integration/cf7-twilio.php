<?php
/*
Plugin Name: Cf7-Twilio integration
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description: This plugin allow users to send messages using twilio api, and plugin is also integrated with contact form 7.
Version: 1.0
Author: Zaheer Abbas Aghani - Joey Bolohan
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: cf7-twilio
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
require plugin_dir_path(__FILE__).'/inc/twilio-php-master/src/Twilio/autoload.php';
use Twilio\Rest\Client;
class Cf7TwilioIntegration {

function cf7_if_contact_form_not_active($message) {
	if (!is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
	echo $message .= "<div class='notice notice-error is-dismissible'><h4> <a href='https://wordpress.org/plugins/contact-form-7/'> Contact Form 7 </a> Plugin is required to make Cf7-Twilio integration plugin work. Thanks</h4>
		<a href='".get_site_url().'/wp-admin/plugins.php'."'> Go Back</a>
	</div>";
	deactivate_plugins('/cf7-twilio-integration/cf7-twilio.php');
	wp_die();
	}
}




function __construct() {
	add_action( 'admin_init', array($this,'cf7_if_contact_form_not_active' ));
	add_action('init', array($this, 'bpem_start_from_here'));
	add_action('admin_enqueue_scripts', array($this, 'bpem_enqueue_script_front'));
	add_action('admin_menu', array($this,'wpdocs_register_my_custom_submenu_page'));
	add_action('wpcf7_before_send_mail',  array($this, 'CF7Api_process'), 1);
}



function bpem_start_from_here() {
	
	
	require_once plugin_dir_path(__FILE__) . 'cf7_twilio_back/cf7_twilio_tab.php';
	
	
}

// Enqueue Style and Scripts

function bpem_enqueue_script_front() {
//Style
wp_enqueue_style('cf7_twilio-style', plugins_url('assets/css/cf7_twilio.css', __FILE__),'1.0.0','all');

wp_enqueue_script( "cf7_twilio_script", plugins_url('assets/js/cf7_twilio.js', __FILE__), array( 'jquery' ), '1.0.0', true );

}


/* 
	This Code will show twilio settings submenu page below contact form 7 page
*/
 
function wpdocs_register_my_custom_submenu_page() {
    add_submenu_page(
        'wpcf7',
        'Twilio SMS Settings',
        'Twilio SMS Settings',
        'manage_options',
        'twilio-sms-settings',
        array($this,'cf7_twilio_information_callback' ));
    add_action( 'admin_init', array($this,'register_my_cool_plugin_settings' ));
}

/*
	Creating and saving twillio setting fields
*/
function register_my_cool_plugin_settings() {
	//register our settings
	register_setting( 'cf7_twilio_settings_fields_group', 'twilio_sid' );
	register_setting( 'cf7_twilio_settings_fields_group', 'auth_token' );
	register_setting( 'cf7_twilio_settings_fields_group', 'twilio_phone_number' );
	register_setting( 'cf7_twilio_settings_fields_group', 'country_code' );
}

 
function cf7_twilio_information_callback() {
    echo '<div class="wrap twilio_settings"><div id="icon-tools" class="icon32"></div>';
        echo '<h2>Twilio Settings</h2><hr><h3>All Settings</h3>'; ?>

<?php settings_errors(); ?>
<form method="post" action="options.php">
    <?php settings_fields( 'cf7_twilio_settings_fields_group' ); ?>
    <?php do_settings_sections( 'cf7_twilio_settings_fields_group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Twilio SID:</th>
        <td><p style="color: #888; font-style: italic;">Your Account SID and Auth Token from twilio.com/console</p><input type="text" name="twilio_sid" value="<?php echo esc_attr( get_option('twilio_sid') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Auth Token:</th>
        <td><input type="text" name="auth_token" value="<?php echo esc_attr( get_option('auth_token') ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Twilio Phone Number:</th>
        <td><p style="color: #888; font-style: italic;">A Twilio number you own with SMS capabilities Format +17141234567</p><input type="text" name="twilio_phone_number" value="<?php echo esc_attr( get_option('twilio_phone_number') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Country Code:</th>
        <td><input type="phone" name="country_code" value="<?php echo esc_attr( get_option('country_code') ); ?>" /></td>
        </tr>

    </table>
    
    <?php submit_button(); ?>

</form>


<?php         
    echo '</div>';

}

// Format Contact Form 7 Tags into names
public function get_cf7_tagS_To_String($value,$form){
	if(function_exists('wpcf7_mail_replace_tags')) {
		$return = wpcf7_mail_replace_tags($value); 
	} elseif(method_exists($form, 'replace_mail_tags')) {
		$return = $form->replace_mail_tags($value); 
	} else {
		return;
	}
	return $return;
}
 

function CF7Api_process($cfdata) {


    $options = get_option( 'wpcf7_international_sms_' . (method_exists($cfdata, 'id') ? $cfdata->id() : $cfdata->id));
	//for admin
	$sms_to_numbers = $this->get_cf7_tagS_To_String($options['sms_to_numbers'],$cfdata);;
	$sms_from_numbers=$this->get_cf7_tagS_To_String($options['sms_from_numbers'],$cfdata);
	$message = $this->get_cf7_tagS_To_String($options['message'],$cfdata);
	
	//for visitor
	$visitorNumber = $this->get_cf7_tagS_To_String($options['visitorNumber'],$cfdata); 
	$visitorMessage = $this->get_cf7_tagS_To_String($options['visitorMessage'],$cfdata); 

	$permission = $this->get_cf7_tagS_To_String($options['permission'],$cfdata); 
	
	$user_numbers = explode(",",$sms_to_numbers);
	
	
	// Your Account SID and Auth Token from twilio.com/console
	/*$account_sid = get_option('twilio_sid') ;
	$auth_token = get_option('auth_token');*/

	$account_sid = get_option('twilio_sid');
	$auth_token = get_option('auth_token');
	$country_code = get_option('country_code');
	/*$account_sid = 'AC1287117890cde7073b488b95fed6c79b';
	$auth_token = '7a005f931ee4b247507e992260af074f';*/


	// In production, these should be environment variables. E.g.:
	// $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
	// A Twilio number you own with SMS capabilities
	$twilio_number = $sms_from_numbers;
	$client = new Client($account_sid, $auth_token);
	
	// Send message for Admins
	foreach ($user_numbers as $value) {
		$client->messages->create(
		 	$country_code.''.$value,
				array(
				 'from' => $twilio_number,
				 'body' => $message
				)
		);
	}

	// Send message for visitors
	if($permission=="Yes"){
		$client->messages->create(
		 	$visitorNumber,
				array(
				 'from' => $twilio_number,
				 'body' => $visitorMessage
				)
		);
	}
	
}


} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('Cf7TwilioIntegration')) {
$obj = new Cf7TwilioIntegration();
}