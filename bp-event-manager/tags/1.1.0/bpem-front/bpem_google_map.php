<?php

function bpem_google_map_init() {

	if (is_single('bpem_event')) {
		global $post;
		$user_ids = get_post_meta($post->ID, 'event_attend_id');
		$location = get_post_meta(get_the_id(), 'evn_location');
		echo $location[0];
	}

	?>
<form method="post">
	<input type="text" id="address" value="pakistan">
	<input type="text" id="latlong" value="">
</form>

<div id="map"></div>
<?php
$lat = 25.1921464;
	$lng = 66.5949955;
	?>
<script>

function convertAddress() {
	var geocoder;
	var address = document.getElementById("address").value;
	console.log(address);
	geocoder = new google.maps.Geocoder();
	geocoder.geocode( { 'address': address},
		function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				document.getElementById('latlong').value = (results[0].geometry.location);
			} else {
				alert("Geocode was not successful for the following reason: " + status);
			}
		});
}


function initMap() {

/*var geocoder;
	var address = document.getElementById("address").value;
	//console.log(address);
	geocoder = new google.maps.Geocoder();
	geocoder.geocode( { 'address': address},
		function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				document.getElementById('latlong').value = (results[0].geometry.location);
			} else {
				alert("Geocode was not successful for the following reason: " + status);
			}
		});*/

var uluru = {lat: <?php echo $lat; ?>, lng: <?php echo $lng; ?>};
var map = new google.maps.Map(
document.getElementById("map"), {zoom: 4, center: uluru});
var marker = new google.maps.Marker({position: uluru, map: map});
}
</script>

<?php }

add_shortcode('bpem_map', 'bpem_google_map_init');