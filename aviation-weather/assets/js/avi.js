jQuery(document).ready(function(){

	jQuery("#avi_aviation_weather_process").submit(function(e){

		e.preventDefault();

		jQuery(".avi_response").css("opacity","0.5");

		var val = jQuery(this).find("input[type='text']").val();
		var data = {
			'action': 'avi_aviation_weather_search',
			'val': val
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(avi_ajax_object.ajax_url, data, function(response) {
			//console.log( response);
			jQuery(".avi_response").css("opacity","1");
			jQuery(".avi_response").html(response);
		});

	})


});