jQuery(document).ready(function() {

	jQuery('body').on('submit', '#dp_searchForm', function(e) {
		e.preventDefault();
		var val = jQuery(this).find("input[type='number']").val();
		//alert(val);
		var data = {
			'action': 'dp_get_post_by_value',
			'val': val,
			 dataType: "json",
			//'post_id':post_id
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(dp_object.ajax_url, data, function(response) {
			//	console.log(response);
			if(response.response == 0){
				jQuery(".dp_response").html("<p style='margin-top:10px;'>"+response.message+"</p>");				
			}else{
				jQuery(".dp_response").html("<p style='margin-top:10px;'>"+response.message+"</p>");	
				window.location.replace(response.redirect);	
			}			
		});
	});
});