<?php
/*
Plugin Name: CF7 Api Integration
Plugin URI: http://wordpresswithzaheer.blogspot.com/p/plugin.html
Description: Plugin is used to integrate api with Contact form 7.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: cf7apiintegration
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class CF7ApiIntegration {

function __construct() {
	add_action('init', array($this, 'bpem_start_from_here'));
	add_action('wp_enqueue_scripts', array($this, 'bpem_enqueue_script_front'));
	add_action('wpcf7_before_send_mail',  array($this, 'CF7Api_process'), 1);
	
}



function bpem_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'cv7_api_front/cv7_api_integration_process.php';
	require_once plugin_dir_path(__FILE__) . 'cv7_api_front/cf7api-display.php';
	$streetAddress = str_replace(' ', '%20',"7 Vinal St");
	$cityTown = "Dracut";
	$zip = "01826";
	$input_bill = "150";

/*$streetAddress = str_replace(' ', '%20', $streetAddress); 
$state = str_replace(' ', '%20', $state); */


	/*$url= "http://3.88.244.193:5000/home?streetAddress=".$streetAddress."&cityTown=".$cityTown."&state=MA&zip=".$zip."&input_bill=".$input_bill."";
//echo $url;
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = [
        'Accept: application/json',
        'Accept-Encoding:deflate'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $server_output = curl_exec ($ch);
    curl_close ($ch);
    $outputResponse = json_decode($server_output, true);
   echo "<pre>";
   print_r($outputResponse);
	echo "</pre>";
	
	
	    if ($outputResponse['dis_prl'] != ''){
		echo "Success";
     	        global $wpdb;
         $tablename = $wpdb->prefix.'quoteapi';
         $dis_prl = $outputResponse['dis_prl'];
         $dis_prh = $outputResponse['dis_prh'];
         $max_kw = $outputResponse['max_kw'];
         $prod_25 = $outputResponse['prod_25'];
         $carb_dio = $outputResponse['carb_dio'];
         $co2_offset = $outputResponse['co2_offset'];
         $cars_off = $outputResponse['cars_off'];
         $tree_equiv = $outputResponse['tree_equiv'];
         $water_saved = $outputResponse['water_saved'];
         $image_url = $outputResponse['image_url'];
         $area = $outputResponse['area'];
         $hours = $outputResponse['hours'];
         $input_bill = $outputResponse['input_bill'];
         $min_kw = $outputResponse['min_kw'];
         $needed_cap = $outputResponse['needed_cap'];
         $rec_size = $outputResponse['rec_size'];
         $use_area = $outputResponse['use_area'];
         $avo_bi_25 = $outputResponse['avo_bi_25'];
         $depprod_25 = $outputResponse['depprod_25'];
         $savings = $outputResponse['savings'];
         $simple_roi = $outputResponse['simple_roi'];
         $smart = $outputResponse['smart'];
         $yr1_prod = $outputResponse['yr1_prod'];

          $sql = $wpdb->prepare("INSERT INTO $tablename (dis_prl, dis_prh, max_kw,prod_25,carb_dio,co2_offset,cars_off,tree_equiv,water_saved,image_url,area,hours,input_bill,min_kw,needed_cap,rec_size,use_area,avo_bi_25,depprod_25,savings,simple_roi,smart,yr1_prod) VALUES ('$dis_prl', '$dis_prh', '$max_kw', '$prod_25','$carb_dio','$co2_offset','$cars_off','$tree_equiv','$water_saved','$image_url','$area','$hours','$input_bill','$min_kw','$needed_cap','$rec_size','$use_area','$avo_bi_25','$depprod_25','$savings','$simple_roi','$smart','$yr1_prod')"  );
         $wpdb->query($sql);*/

        /*$wpdb->insert( $tablename, array(
		    'dis_prl' => $outputResponse['dis_prl'], 
            'dis_prh' => $outputResponse['dis_prh'],
            'max_kw' => $outputResponse['max_kw'], 
			 'prod_25' => $outputResponse['prod_25'],
            'carb_dio' => $outputResponse['carb_dio'], 
            'co2_offset' => $outputResponse['co2_offset'], 
            'cars_off' => $outputResponse['cars_off'],
            'tree_equiv' => $outputResponse['tree_equiv'], 
            'water_saved' => $outputResponse['water_saved'], 
            'image_url' => $outputResponse['image_url'],
			'area' => $outputResponse['area'],
            'hours' => $outputResponse['hours'], 
            'input_bill' => $outputResponse['input_bill'], 
            'min_kw' => $outputResponse['min_kw'],
            'needed_cap' => $outputResponse['needed_cap'], 
            'rec_size' => $outputResponse['rec_size'], 
            'use_area' => $outputResponse['use_area'],
			'avo_bi_25' => $outputResponse['avo_bi_25'],
			'depprod_25' => $outputResponse['depprod_25'],
			'savings' => $outputResponse['savings'],
			'simple_roi' => $outputResponse['simple_roi'],
			'smart' => $outputResponse['smart'],
			'yr1_prod' => $outputResponse['yr1_prod']
			),
            array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s') 
        );
    }
*/
//echo "Hello World";

//	$street = get_post_meta( 1, 'street',false);
//	print_r($street);
}

// Enqueue Style and Scripts

function bpem_enqueue_script_front() {
//Style & Script
wp_enqueue_style('cv19-style', plugins_url('assets/css/cv19.css', __FILE__),'1.0.0','all');
wp_enqueue_script('cf7api-script', plugins_url('assets/js/cf7api.js', __FILE__),array('jquery'),'1.0.0', true);


	
	

}


function CF7Api_process($cfdata) {
    if (!isset($cfdata->posted_data) && class_exists('WPCF7_Submission')) {
        // Contact Form 7 version 3.9 removed $cfdata->posted_data and now
        // we have to retrieve it from an API
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $formdata = $submission->get_posted_data();
        }
    } elseif (isset($cfdata->posted_data)) {
        // For pre-3.9 versions of Contact Form 7
        $formdata = $cfdata->posted_data;
    } else {
        // We can't retrieve the form data
        return $cfdata;
    }
    // Check this is the user registration form
    if ( $cfdata->title() == 'my test API') {

    	$streetAddress =str_replace(' ', '%20', $formdata['street']);
        $cityTown = $formdata['city'];
        $state = $formdata['State'];
        if($state=="Massachusetts"){
        	$state = "MA";
        }
        if($state=="New Hampshire"){
        	$state = "NH";
        }
        if($state=="Texas"){
        	$state = "TX";
        }
        $zip = $formdata['zip'];
        $input_bill = $formdata['input_bill'];

		//$streetAddress = str_replace(' ', '%20', $streetAddress); 
		//$state = str_replace(' ', '%20', $state); 

$url= "http://3.88.244.193:5000/home?streetAddress=".$streetAddress."&cityTown=".$cityTown."&state=".$state."&zip=".$zip."&input_bill=".$input_bill."";
//echo $url;
$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = [
        'Accept: application/json',
        'Accept-Encoding:deflate'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $server_output = curl_exec ($ch);
    curl_close ($ch);
    $outputResponse = json_decode($server_output, true);
   // echo "<pre>";
   // print_r($outputResponse);
	// echo "</pre>";
	
	
	if ($outputResponse['dis_prl'] != ''){

         global $wpdb;
         $tablename = $wpdb->prefix.'quoteapi';
         $dis_prl = $outputResponse['dis_prl'];
         $dis_prh = $outputResponse['dis_prh'];
         $max_kw = $outputResponse['max_kw'];
         $prod_25 = $outputResponse['prod_25'];
         $carb_dio = $outputResponse['carb_dio'];
         $co2_offset = $outputResponse['co2_offset'];
         $cars_off = $outputResponse['cars_off'];
         $tree_equiv = $outputResponse['tree_equiv'];
         $water_saved = $outputResponse['water_saved'];
         $image_url = $outputResponse['image_url'];
         $area = $outputResponse['area'];
         $hours = $outputResponse['hours'];
         $input_bill = $outputResponse['input_bill'];
         $min_kw = $outputResponse['min_kw'];
         $needed_cap = $outputResponse['needed_cap'];
         $rec_size = $outputResponse['rec_size'];
         $use_area = $outputResponse['use_area'];
         $avo_bi_25 = $outputResponse['avo_bi_25'];
         $depprod_25 = $outputResponse['depprod_25'];
         $savings = $outputResponse['savings'];
         $simple_roi = $outputResponse['simple_roi'];
         $smart = $outputResponse['smart'];
         $yr1_prod = $outputResponse['yr1_prod'];

         $sql = $wpdb->prepare("INSERT INTO $tablename (dis_prl, dis_prh, max_kw,prod_25,carb_dio,co2_offset,cars_off,tree_equiv,water_saved,image_url,area,hours,input_bill,min_kw,needed_cap,rec_size,use_area,avo_bi_25,depprod_25,savings,simple_roi,smart,yr1_prod) VALUES ('$dis_prl', '$dis_prh', '$max_kw', '$prod_25','$carb_dio','$co2_offset','$cars_off','$tree_equiv','$water_saved','$image_url','$area','$hours','$input_bill','$min_kw','$needed_cap','$rec_size','$use_area','$avo_bi_25','$depprod_25','$savings','$simple_roi','$smart','$yr1_prod')"  );
         $wpdb->query($sql);
    
    }

    }
    return $cfdata;
}

} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('CF7ApiIntegration')) {
$obj = new CF7ApiIntegration();
}