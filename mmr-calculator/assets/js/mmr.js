jQuery(document).ready(function(){

	jQuery("#mmr_calculator").on("submit", function(e){

		e.preventDefault();

		var region = jQuery(".mmr_calculator input[type='radio']:checked").val();
		var summoner_name = jQuery("#summoner_name").val();

		var data = {
			'action': 'mmr_get_information',
			'region': region,
			'summoner_name':summoner_name
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(mmr_ajax_object.ajax_url, data, function(response) {
			console.log(response);
			jQuery(".mmr_response").html(response);
		});

	});

});