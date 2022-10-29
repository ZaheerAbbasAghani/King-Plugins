<?php

/*

Plugin Name: Solar Production

Plugin URI: https://www.fiverr.com/zaheerabbasagha

Description: This plugin is used to calculates the solar production of solar panels.

Version: 1.0

Author: Zaheer Abbas Aghani

Author URI: https://profiles.wordpress.org/zaheer01/

License: GPLv3 or later

Text Domain: solar-production

Domain Path: /languages

*/



defined("ABSPATH") or die("No direct access!");

class SolarProduction {



function __construct() {
	
	add_action('init', array($this, 'sol_start_from_here'));

	add_action('wp_enqueue_scripts', array($this, 'sol_enqueue_script_front'));

	add_action('admin_enqueue_scripts', array($this, 'sol_enqueue_script_admin'));
	add_action('init', array($this, 'sol_create_table'));
	add_action('init', array($this, 'sol_print_pdf'));
	define('SOL_SECRET_KEY', '60119fa00df937.74315854');
	define('SOL_SERVER_URL', 'https://jump-in-shape.com');
	define('SOL_ITEM_REFERENCE', 'Solar Production'); 

}



function sol_start_from_here() {

	require_once plugin_dir_path(__FILE__).'front/sol_search_form.php';

	require_once plugin_dir_path(__FILE__).'front/sol_searched_location_process.php';

	require_once plugin_dir_path(__FILE__).'front/sol_insert_pricing.php';
	require_once plugin_dir_path(__FILE__).'front/sol_delete_pricing.php';
	require_once plugin_dir_path(__FILE__).'front/sol_edit_pricing.php';

	require_once plugin_dir_path(__FILE__).'back/sol_settings.php';

	require_once plugin_dir_path(__FILE__).'front/mpdf_mpdf_8.0.8.0_require/vendor/autoload.php';
	require_once plugin_dir_path(__FILE__).'front/mpdf_mpdf_8.0.8.0_require/vendor/mpdf/sollicg.php';
}



// Enqueue Style and Scripts



function sol_enqueue_script_front() {

//Style & Script

wp_enqueue_style('sol-style','https://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css');

wp_enqueue_style('sol-own', plugins_url('assets/css/sol.css', __FILE__),'1.0.0','all');



$g_url =  esc_attr( get_option('api_url') );
wp_enqueue_script('sol-gapi',"https://maps.googleapis.com/maps/api/js?fields=formatted_address,name,geometry&key=".$g_url."&libraries=places");




wp_enqueue_script('sol-chart', 'https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js');


wp_enqueue_script('sol-script', plugins_url('assets/js/sol.js', __FILE__),array('jquery'),'1.0.0', true);

$graphClr = get_option('graphColor');
$boxClr = get_option('outputBoxColors');
$boxtxtClr = get_option('outputBoxTextColors');
$graphMonthColorFont = get_option('graphMonthColorFont');
$graphMonthFontSize = get_option('graphMonthFontSize');
$BoxFontSize = get_option('BoxFontSize');
$export = get_option('enable_disable_export');


wp_localize_script( 'sol-script','ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ), "graphColor" => $graphClr, "boxColor" => $boxClr, "outputBoxTextColors" => $boxtxtClr, "graphMonthColorFont" => $graphMonthColorFont, "graphMonthFontSize" => $graphMonthFontSize, "BoxFontSize" => $BoxFontSize , "export" => $export));
/*wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-slider');*/


}


function sol_enqueue_script_admin() {

  wp_enqueue_style( 'wp-color-picker' );


wp_enqueue_style('dataTable','https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css',array(),"1.10.23", false);
wp_enqueue_script('dataTable', 'https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js',array("jquery"),"1.10.23", false);

wp_enqueue_script('sol-admin', plugins_url('assets/js/sol_admin.js', __FILE__),array('jquery','wp-color-picker'),'1.0.0', true);
wp_localize_script( 'sol-admin','ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )));

}


function sol_print_pdf(){
	if(isset($_GET['print_all'])){
		//print_r($_GET);	

global $wpdb; // this is how you get access to the database	
$address = $_GET['address'];
$area1 = $_GET['area1'];
$area_total = $area1 * 6.25;
$area2 = $_GET['area2'];
$area3 = $_GET['area3'];
$dirVal = $_GET['dirValue'];
$ratVal = $_GET['ratioValue'];
$sol_api_url = get_option("sol_api_url");
$apiUrl = 'https://solkollen.se/green/api/calculate2?address='.urlencode($address).'&area1='.$area_total.'&area2='.$area2.'&area3='.$area3.'&dirValue='.$dirVal.'&ratioValue='.$ratVal.'';
$response = wp_remote_get($apiUrl);
$responseBody = wp_remote_retrieve_body( $response );
$result = json_decode( $responseBody );

$table_name = $wpdb->base_prefix.'sol_pricing';
$query = "SELECT * FROM $table_name WHERE kwp_from='$area1'";
$query_results = $wpdb->get_results($query);
foreach ($query_results as $res) {
  $m2 = $res->kwp_m2;
}

if (! is_wp_error( $result ) ) { 
	//print_r($result);

$mpdf = new \Mpdf\Mpdf();
$html .= '<h1 style="text-align:center;font-size:30px;">Så här mycket solel kan du få från ditt tak.</h1><h2 style="text-align:center;">Sammanställning</h2><p style="text-align:center;font-weight:bold;">(kWh) </p>';

	$html .="<div class='container' style='margin-top:50px;'>";
	$html .= "<div style='background:#81bef7;float:left;width:45px;height:40px;margin-left:12px;text-align:center;padding-top:20px;'>".$result->areas->month_es_1[0]."</div>";

	$html .= "<div style='background:#81bef7;float:left;width:45px;height:80px;margin-left:12px;text-align:center;padding-top:20px;'>".$result->areas->month_es_1[1]."</div>";

	$html .= "<div style='background:#81bef7;float:left;width:45px;height:180px;margin-left:12px;text-align:center;padding-top:20px;'>".$result->areas->month_es_1[2]."</div>";

	$html .= "<div style='background:#81bef7;float:left;width:45px;height:280px;margin-left:12px;text-align:center;padding-top:20px;'>".$result->areas->month_es_1[3]."</div>";

	$html .= "<div style='background:#81bef7;float:left;width:45px;height:320px;margin-left:12px;text-align:center;padding-top:20px;'>".$result->areas->month_es_1[4]."</div>";

	$html .= "<div style='background:#81bef7;float:left;width:45px;height:380px;margin-left:12px;text-align:center;padding-top:20px;'>".$result->areas->month_es_1[5]."</div>";

	$html .= "<div style='background:#81bef7;float:left;width:45px;height:370px;margin-left:12px;text-align:center;padding-top:20px;'>".$result->areas->month_es_1[6]."</div>";

	$html .= "<div style='background:#81bef7;float:left;width:45px;height:320px;margin-left:12px;text-align:center;padding-top:20px;'>".$result->areas->month_es_1[7]."</div>";

	$html .= "<div style='background:#81bef7;float:left;width:45px;height:280px;margin-left:12px;text-align:center;padding-top:20px;'>".$result->areas->month_es_1[8]."</div>";

	$html .= "<div style='background:#81bef7;float:left;width:45px;height:180px;margin-left:12px;text-align:center;padding-top:20px;'>".$result->areas->month_es_1[9]."</div>";

	$html .= "<div style='background:#81bef7;float:left;width:45px;height:80px;margin-left:12px;text-align:center;padding-top:20px;'>".$result->areas->month_es_1[10]."</div>";

	$html .= "<div style='background:#81bef7;float:left;width:45px;height:40px;margin-left:12px;text-align:center;padding-top:20px;'>".$result->areas->month_es_1[11]."</div>";

	$html .="</div>";


$html .='<div class="chartbox" style="margin-top:20px;margin-left:60px;">';
	$html .='<div class="storlek" style="text-align:center;background: rgb(30, 115, 190); width: 180px;float:left;padding-top:40px;height: 150px;margin-bottom:20px;"><p class="label" style="color: rgb(255, 255, 255); font-size: 18px;border-radius:50px;"> Kvadratmeter </p><p class="val" style="color: rgb(255, 255, 255); font-size: 18px;">'. $m2.'</p>
	</div>';
	$html .='<div class="box2" style="text-align:center;background: rgb(30, 115, 190);width: 180px;float:left;padding-top:40px;height: 150px;vertical-align: bottom;margin-left:10px;"><p class="label" style="color: rgb(255, 255, 255); font-size: 18px;"> Storlek på anläggningen i kWp </p><p class="val" style="color: rgb(255, 255, 255); font-size: 18px;">'.$area1.'</p></div>';

	$html .='<div class="box3" style="text-align:center;background: rgb(30, 115, 190);width: 180px;float:left;padding-top:40px;height: 150px;vertical-align: bottom;margin-left:10px;"><p class="label" style="color: rgb(255, 255, 255); font-size: 18px;"> Investering efter grön ROT i kr </p><p class="val" style="color: rgb(255, 255, 255); font-size: 18px;">'.$_GET['pricing'].'</p></div>';

	$html .='<div class="box4" style="text-align:center;background: rgb(30, 115, 190);width: 180px;float:left;padding-top:40px;height: 150px;"><p class="label" style="color: rgb(255, 255, 255); font-size: 18px;"><p class="label" style="color: rgb(255, 255, 255); font-size: 18px;"> Årsproduktion i kWh </p><p class="val" style="color: rgb(255, 255, 255); font-size: 18px;">'.$result->areas->yearly_production_1.'</p></div>';

	$html .='<div class="box5" style="text-align:center;background: rgb(30, 115, 190);width: 180px;float:left;padding-top:40px;height: 150px;margin-left:10px;"><p class="label" style="color: rgb(255, 255, 255); font-size: 18px;"><p class="label" style="color: rgb(255, 255, 255); font-size: 18px;"> Besparing per år i kr </p><p class="val" style="color: rgb(255, 255, 255); font-size: 18px;">'.$result->areas->yearly_saving_1.'</p></div>';

	//$sum = parseInt(jQuery("#pricing").val()) / parseInt(obj.full.yearly_saving_1);

	$sum = intval($_GET['pricing']) / intval($result->areas->yearly_saving_1);

	$html .='<div class="box6" style="text-align:center;background: rgb(30, 115, 190);width: 180px;float:left;padding-top:40px;height: 150px;vertical-align: bottom;margin-left:10px;"><p class="label" style="color: rgb(255, 255, 255); font-size: 18px;"><p class="label" style="color: rgb(255, 255, 255); font-size: 18px;"> Återbetalningstid i antal år </p><p class="val" style="color: rgb(255, 255, 255); font-size: 18px;">'.number_format($sum,3).'</p></div>';
$html .='</div>';

$mpdf->WriteHTML($html);
$mpdf->Output('production.pdf', 'I');	

//============================================================+
// END OF FILE
//============================================================+


}else{
	echo "Something went wrong";
}

	die();

	}

}



function sol_create_table(){

    global $wpdb;
    $table_name = $wpdb->base_prefix.'sol_edit_pricing';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          kwp_from varchar(20) NOT NULL,
          kwp_m2 varchar(20) NOT NULL,
          pricing varchar(20) NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }



  	global $wpdb;
    $table_name = $wpdb->base_prefix.'sol_confirm';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
    if ( ! $wpdb->get_var( $query ) == $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id mediumint(255) NOT NULL AUTO_INCREMENT,
          sol_license varchar(255) NOT NULL,
          status varchar(2) NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }


 //$wpdb->query( "DROP TABLE IF EXISTS $table_name" );

/*$delete = $wpdb->query("TRUNCATE TABLE $table_name");
$mytables=$wpdb->get_results("SHOW TABLES");
foreach ($mytables as $mytable)
{
    foreach ($mytable as $t) 
    {       
        echo $t . "<br>";
    }
}
*/
}


} // class ends



// CHECK WETHER CLASS EXISTS OR NOT.



if (class_exists('SolarProduction')) {

$obj = new SolarProduction();

}