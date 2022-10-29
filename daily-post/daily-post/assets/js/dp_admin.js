jQuery(document).ready(function() {
	/*jQuery("#dp_day_of_year_number").blur(function(){
		var num = jQuery(this).val();
		var post_id = jQuery(this).attr("data-id");
		var data = {
			'action': 'dp_save_day_of_year_number',
			'num': num,
			'post_id':post_id
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(dp_object.ajax_url, data, function(response) {
			alert(response);
			//jQuery("#dp_day_of_year_number").val('');
		});
	});
*/
	jQuery('body').on('blur', '#find_day_number', function() {
	//jQuery("#find_day_number").blur(function(){
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