<?php 

//require_once __DIR__ . '/vendor/autoload.php';


add_action( 'wp_ajax_avi_aviation_weather_search', 'avi_aviation_weather_search');
add_action( 'wp_ajax_nopriv_avi_aviation_weather_search','avi_aviation_weather_search');
function avi_aviation_weather_search() {
	global $wpdb;

$api_key = get_option('avi_api_key');
$val_without_comma = explode(" ", $_POST['val']);

$val_with_comma = implode(", ", $val_without_comma);

//echo $val_with_comma;

// Metar 

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.checkwx.com/metar/'.$val_with_comma.'/decoded');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-API-Key: $api_key"]);
$resp = curl_exec($ch);
$metar = json_decode($resp)->data;


$sideOne = "";
$sideOne .= '<div class="LeftSide" style="margin-right:20px;">';

	foreach ($metar as $value) {
		$sideOne .= '<div class="col-12 col-md-6">
      <div class="card card-tables">
        <div class="card-header bg-secondary justify-content-between align-items-center">
          
          <h3 class="m-0 text-white"> METAR - '.$value->icao.'</h3>
          
        </div>
        <div class="card-body">
          <table class="table">
            <tbody>
              <tr>
                <th colspan="2">'.$value->raw_text.'</th>
              </tr>

					</tbody></table>
        </div>
      </div>
    </div>';
	}
	

$sideOne .= '</div>';

//echo $sideOne;


// TAF

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.checkwx.com/taf/'.$val_with_comma.'/decoded');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-API-Key: $api_key"]);
$resp = curl_exec($ch);
$taf = json_decode($resp)->data;



$sideTwo = "";
$sideTwo .= '<div class="LeftSide" style="margin-right:20px;">';

foreach ($taf as $value) {
	$sideTwo .= '<div class="col-12 col-md-6">
    <div class="card card-tables">
      <div class="card-header bg-secondary justify-content-between align-items-center">
        
        <h3 class="m-0 text-white"> TAF REPORT - '.$value->icao.'</h3>
        
      </div>
      <div class="card-body">
        <table class="table">
          <tbody>
            <tr>
              <th colspan="2">'.$value->raw_text.'</th>
            </tr>

				</tbody></table>
      </div>
    </div>
  </div>';
}
$sideTwo .= '</div>';
	
echo $sideOne;
echo $sideTwo;



	wp_die();
}