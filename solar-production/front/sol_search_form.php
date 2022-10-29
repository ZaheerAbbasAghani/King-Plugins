<?php



function sol_search_form($search_form){

        $license_key = get_option('sol_license_key');
   // API query parameters
        $api_params = array(
            'slm_action' => 'slm_activate',
            'secret_key' => SOL_SECRET_KEY,
            'license_key' => $license_key,
            'registered_domain' => $_SERVER['SERVER_NAME'],
            'item_reference' => urlencode(SOL_ITEM_REFERENCE),
        );

        // Send query to the license manager server
        $query = esc_url_raw(add_query_arg($api_params, SOL_SERVER_URL));
        $response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));

        // Check for error in the response
        if (is_wp_error($response)){
            echo "Unexpected Error! The query returned with an error.";
        }

        //var_dump($response);//uncomment it if you want to look at the full response
        
        // License data.
        $license_data = json_decode(wp_remote_retrieve_body($response));
        
        //print_r($license_data);

        // TODO - Do something with it.
        //var_dump($license_data);//uncomment it to look at the data
        
        if($license_data->result == 'success'){

$search_form .= '<html>
<head>
<link type="text/css" rel="stylesheet" media="all" href="https://www.solkollen.nu/test/sites/all/libraries/jquery.ui/themes/default/ui.all.css" />
<script src="https://www.solkollen.nu/test/sites/all/libraries/jquery.ui/jquery-1.2.6.js"></script>
<script src="https://www.solkollen.nu/test/sites/all/libraries/jquery.ui/ui/minified/jquery.ui.all.min.js"></script>
<style>
#ratio {
    position: relative;
    width: 200px;
    height: 80px;
    margin-left: auto;
    margin-right: auto;
}

#ratio-center {
    width: 10px;
    height: 10px;
    border-radius: 10px;
    background-color: #ffffff;
    border: solid 1px #cccccc;
    position: absolute;
    top: 5px;
    left: 95px;
    z-index: 90;
}

#ratio-left {
    width: 60px;
    height: 8px;
    border-radius: 8px;
    background-color: #005A84;
    box-shadow: 1px 1px 1px #666666;
    position: absolute;
    top: 6px;
    left: 104px;
    z-index: 80;
    transform-origin: left top;
    -moz-transform-origin: left top;
    -webkit-transform-origin: left top;
}

.rotate-left-45 {
    transform: rotate(45deg);
    -webkit-transform: rotate(45deg);
    -moz-transform: rotate(45deg);
}

#ratio-right {
    width: 60px;
    height: 8px;
    border-radius: 8px;
    background-color: #005A84;
    box-shadow: 1px 1px 1px #666666;
    position: absolute;
    top: 6px;
    left: 37px;
    z-index: 80;
    transform-origin: right top;
    -moz-transform-origin: right top;
    -webkit-transform-origin: right top;
}

.rotate-right-45 {
    transform: rotate(-45deg);
    -webkit-transform: rotate(-45deg);
    -moz-transform: rotate(-45deg);
}

#ratio-value, #dir-value {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    color: #005A84;
    line-height: 150%;
}

#dir {
    width: 200px;
    height: 200px;
    margin-left: auto;
    margin-right: auto;
    position: relative;
}

#dir-block {
    width: 100px;
    height: 60px;
    box-shadow: 1px 1px 5px 1px #666666;
    position: absolute;
    left: 50px;
    top: 70px;
    transform-origin: center center;
    -moz-transform-origin: center center;
    -webkit-transform-origin: center center;
}

#dir-block-top {
    width: 100px;
    height: 30px;
    background-color: #dddddd;
}

#dir-block-bottom {
    width: 100px;
    height: 30px;
    background-color: #f7f034;
}

ui-slider {
    margin: 0;
    padding: 0;
    border: 0;
    outline: 0;
    line-height: 1.3;
    text-decoration: none;
    font-size: 100%;
    list-style: none;
    font-family: Verdana,Arial,sans-serif;
    background: #ffffff;
    border: 1px solid #d3d3d3;
    height: .8em;
    position: relative;
}

.slider-chart {
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    max-width: 300px;
}

.ui-slider-handle,.ui-slider-handle:hover {
    width: 30px;
    border: none;
    background: transparent url(https://www.solkollen.nu/test/sites/all/themes/zeropoint/images/arrows.png) no-repeat center center;
}

.dir-right-10{transform:rotate(-10deg);-webkit-transform:rotate(-10deg);-moz-transform:rotate(-10deg);}
.dir-left-20{transform:rotate(20deg);-webkit-transform:rotate(20deg);-moz-transform:rotate(20deg);}
.dir-right-20{transform:rotate(-20deg);-webkit-transform:rotate(-20deg);-moz-transform:rotate(-20deg);}
.dir-left-30{transform:rotate(30deg);-webkit-transform:rotate(30deg);-moz-transform:rotate(30deg);}
.dir-right-30{transform:rotate(-30deg);-webkit-transform:rotate(-30deg);-moz-transform:rotate(-30deg);}
.dir-left-40{transform:rotate(40deg);-webkit-transform:rotate(40deg);-moz-transform:rotate(40deg);}
.dir-right-40{transform:rotate(-40deg);-webkit-transform:rotate(-40deg);-moz-transform:rotate(-40deg);}
.dir-left-50{transform:rotate(50deg);-webkit-transform:rotate(50deg);-moz-transform:rotate(50deg);}
.dir-right-50{transform:rotate(-50deg);-webkit-transform:rotate(-50deg);-moz-transform:rotate(-50deg);}
.dir-left-60{transform:rotate(60deg);-webkit-transform:rotate(60deg);-moz-transform:rotate(60deg);}
.dir-right-60{transform:rotate(-60deg);-webkit-transform:rotate(-60deg);-moz-transform:rotate(-60deg);}
.dir-left-70{transform:rotate(70deg);-webkit-transform:rotate(70deg);-moz-transform:rotate(70deg);}
.dir-right-70{transform:rotate(-70deg);-webkit-transform:rotate(-70deg);-moz-transform:rotate(-70deg);}
.dir-left-80{transform:rotate(80deg);-webkit-transform:rotate(80deg);-moz-transform:rotate(80deg);}
.dir-right-80{transform:rotate(-80deg);-webkit-transform:rotate(-80deg);-moz-transform:rotate(-80deg);}
.dir-left-90{transform:rotate(90deg);-webkit-transform:rotate(90deg);-moz-transform:rotate(90deg);}
.dir-right-90{transform:rotate(-90deg);-webkit-transform:rotate(-90deg);-moz-transform:rotate(-90deg);}

.rotate-left-5{transform:rotate(5deg);-webkit-transform:rotate(5deg);-moz-transform:rotate(5deg);}
.rotate-right-5{transform:rotate(-5deg);-webkit-transform:rotate(-5deg);-moz-transform:rotate(-5deg);}
.rotate-left-10{transform:rotate(10deg);-webkit-transform:rotate(10deg);-moz-transform:rotate(10deg);}
.rotate-right-10{transform:rotate(-10deg);-webkit-transform:rotate(-10deg);-moz-transform:rotate(-10deg);}
.rotate-left-15{transform:rotate(15deg);-webkit-transform:rotate(15deg);-moz-transform:rotate(15deg);}
.rotate-right-15{transform:rotate(-15deg);-webkit-transform:rotate(-15deg);-moz-transform:rotate(-15deg);}
.rotate-left-20{transform:rotate(20deg);-webkit-transform:rotate(20deg);-moz-transform:rotate(20deg);}
.rotate-right-20{transform:rotate(-20deg);-webkit-transform:rotate(-20deg);-moz-transform:rotate(-20deg);}
.rotate-left-25{transform:rotate(25deg);-webkit-transform:rotate(25deg);-moz-transform:rotate(25deg);}
.rotate-right-25{transform:rotate(-25deg);-webkit-transform:rotate(-25deg);-moz-transform:rotate(-25deg);}
.rotate-left-30{transform:rotate(30deg);-webkit-transform:rotate(30deg);-moz-transform:rotate(30deg);}
.rotate-right-30{transform:rotate(-30deg);-webkit-transform:rotate(-30deg);-moz-transform:rotate(-30deg);}
.rotate-left-35{transform:rotate(35deg);-webkit-transform:rotate(35deg);-moz-transform:rotate(35deg);}
.rotate-right-35{transform:rotate(-35deg);-webkit-transform:rotate(-35deg);-moz-transform:rotate(-35deg);}
.rotate-left-40{transform:rotate(40deg);-webkit-transform:rotate(40deg);-moz-transform:rotate(40deg);}
.rotate-right-40{transform:rotate(-40deg);-webkit-transform:rotate(-40deg);-moz-transform:rotate(-40deg);}
.rotate-left-45{transform:rotate(45deg);-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);}
.rotate-right-45{transform:rotate(-45deg);-webkit-transform:rotate(-45deg);-moz-transform:rotate(-45deg);}
.rotate-left-50{transform:rotate(50deg);-webkit-transform:rotate(50deg);-moz-transform:rotate(50deg);}
.rotate-right-50{transform:rotate(-50deg);-webkit-transform:rotate(-50deg);-moz-transform:rotate(-50deg);}
.rotate-left-55{transform:rotate(55deg);-webkit-transform:rotate(55deg);-moz-transform:rotate(55deg);}
.rotate-right-55{transform:rotate(-55deg);-webkit-transform:rotate(-55deg);-moz-transform:rotate(-55deg);}
.rotate-left-60{transform:rotate(60deg);-webkit-transform:rotate(60deg);-moz-transform:rotate(60deg);}
.rotate-right-60{transform:rotate(-60deg);-webkit-transform:rotate(-60deg);-moz-transform:rotate(-60deg);}
.rotate-left-65{transform:rotate(65deg);-webkit-transform:rotate(65deg);-moz-transform:rotate(65deg);}
.rotate-right-65{transform:rotate(-65deg);-webkit-transform:rotate(-65deg);-moz-transform:rotate(-65deg);}
.rotate-left-70{transform:rotate(70deg);-webkit-transform:rotate(70deg);-moz-transform:rotate(70deg);}
.rotate-right-70{transform:rotate(-70deg);-webkit-transform:rotate(-70deg);-moz-transform:rotate(-70deg);}
.rotate-left-75{transform:rotate(75deg);-webkit-transform:rotate(75deg);-moz-transform:rotate(75deg);}
.rotate-right-75{transform:rotate(-75deg);-webkit-transform:rotate(-75deg);-moz-transform:rotate(-75deg);}
.rotate-left-80{transform:rotate(80deg);-webkit-transform:rotate(80deg);-moz-transform:rotate(80deg);}
.rotate-right-80{transform:rotate(-80deg);-webkit-transform:rotate(-80deg);-moz-transform:rotate(-80deg);}
.rotate-left-85{transform:rotate(85deg);-webkit-transform:rotate(85deg);-moz-transform:rotate(85deg);}
.rotate-right-85{transform:rotate(-85deg);-webkit-transform:rotate(-85deg);-moz-transform:rotate(-85deg);}
.rotate-left-90{transform:rotate(90deg);-webkit-transform:rotate(90deg);-moz-transform:rotate(90deg);}
.rotate-right-90{transform:rotate(-90deg);-webkit-transform:rotate(-90deg);-moz-transform:rotate(-90deg);}
</style>
<script>
function updateDirChart(value){
	var label = "Syd";
	if(value >= 40 && value<70){
		label = "Sydöst";
	}else if(value >= 70){
		label = "Öst";
	}else if(value <= -40 && value>-70){
		label = "Sydväst";
	}else if(value <= -70){
		label = "Väst";
	}
	
	if(value > 0){
		$("#dir-block").attr("class", "dir-right-"+value);
	}else if(value < 0){
		$("#dir-block").attr("class", "dir-left-"+Math.abs(value));
	}else{
		$("#dir-block").attr("class", "");
	}

	jQuery("#dir-value").html(label);
}


jQuery(function(){
var ratioValue = parseFloat($("#ratioValue").val());
	ratioValue = Math.round(ratioValue/5)*5;
	$("#ratio-value").html(ratioValue+"°");
	$("#ratio-left").attr("class", "rotate-left-"+ratioValue);
	$("#ratio-right").attr("class", "rotate-right-"+ratioValue);
	$("#ratio-slider").slider({
		min:0,
		max:90,
		stepping:5,
		startValue: parseFloat($("#ratioValue").val()),
		slide: function(event, ui){
			var value = ui.value;
			value = Math.round(value/5)*5;
			$("#ratioValue").val(value);
			$("#ratio-value").html(value+"°");
			$("#ratio-left").attr("class", "rotate-left-"+value);
			$("#ratio-right").attr("class", "rotate-right-"+value);
			

		}
		});
		
	var dirValue = parseFloat($("#dirValue").val());
	dirValue = Math.round(dirValue/10)*10;
	updateDirChart(dirValue);
	
	$("#dir-slider").slider({
		min:-90,
		max:90,
		stepping:10,
		startValue: parseFloat($("#dirValue").val()),
		slide: function(event, ui){
			var value = ui.value;
			value = Math.round(value/10)*10;
			$("#dirValue").val(value);
			
			updateDirChart(value);
		}
		});
  
});
</script>
</head>
<body style="text-align:center">';

$search_form .= '<div class="mmm"><div class="search_form_wrapper" id="searchLocation"><form action="" method="get">
Address: <input type="text" name="address" id="search_loc" value="" size="60"><br/>';
$search_form .='<select id="select_package" required>
<option value="" selected="selected" disabled>Välj paket</option>';
global $wpdb;
$table_name = $wpdb->base_prefix.'sol_pricing';
$pricing = $wpdb->get_results( "SELECT * FROM $table_name", OBJECT ); 
foreach ($pricing as $row) {
    $search_form .= '<option value="'.$row->kwp_from.'-'.trim($row->pricing).'"> '.$row->kwp_from.' kWp package for the price of '.trim($row->pricing).' kr </option>';
}

$search_form .='</select>';

$search_form .='<input type="hidden" name="area1" id="area1" value=""><br/>
<input type="hidden" name="area2" id="area2" value="1"><br/>
<input type="hidden" name="area3" id="area3" value="1">
<input type="hidden" name="pricing" id="pricing" value="">
<div>
Ange takytans ungefärliga lutning genom att dra i reglaget:
<div id="ratio" style="height:62px;">
  <div id="ratio-center"></div>
  <div id="ratio-left"></div>
  <div id="ratio-right"></div>
</div>
<div id="ratio-value"></div><input type="hidden" name="ratioValue" id="ratioValue" value="45">
<div id="ratio-slider" class="slider-chart"></div>
</div>

<div>
		Ange takytans riktning i förhållande till söder genom att dra i reglaget. Takytan bör ha samma position som på kartan:
		<div id="dir" style="height:100px;">
		  <div id="dir-block" style="top:20px;"><div id="dir-block-top"></div><div id="dir-block-bottom"></div></div>
		</div>
		<div id="dir-value"></div><input type="hidden" name="dirValue" id="dirValue" value="0">
		<div id="dir-slider" class="slider-chart"></div>
</div>

<input type="submit" id="searchLocationSubmit" class="button" value="Beräkna"/>
</form>
</body>
</html> </div>
<div class="maincontainer" style="width:900px;margin:auto; height:450px;display:none;" >

<canvas id="myChart" width="900px" height="400px" ></canvas>
</div>

<div id="previewImage"></div>
</div></div>

';
}else{
    $search_form .= "<p> Activate the license first. </p>";
}

return $search_form;

}

add_shortcode("sol_form","sol_search_form");