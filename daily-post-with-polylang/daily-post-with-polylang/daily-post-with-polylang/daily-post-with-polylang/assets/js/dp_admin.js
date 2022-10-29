jQuery(document).ready(function() {

	jQuery('body').on('blur', '#find_day_number', function() {
	alert();	
		var val = jQuery(this).val();
		//alert(val);
		var data = {
			'action': 'dp_get_post_by_value',
			'val': val,
			//'post_id':post_id
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(dp_object.ajax_url, data, function(response) {
			jQuery(".dp_response").html("<p style='margin-top:10px;'>"+response+"</p>");
		});
	});
});