<?php 

function avi_search_form_html($form){


$form = "";

$form .= "<div class='avi_form_wrapper'> 
<form method='post' action='' id='avi_aviation_weather_process' name='avi_aviation_weather_process'> 
	
	<input type='text' value='' id='avi_search_box'>
	<input type='submit'/>
</form>

<div class='avi_response'></div>

</div>";


return $form;


}

add_shortcode("aviation-weather", "avi_search_form_html");