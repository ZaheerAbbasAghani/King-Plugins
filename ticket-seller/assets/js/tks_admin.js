jQuery(document).ready(function(){

	jQuery(document).on("click",".update_products", function(e){

		e.preventDefault();

		if (confirm('Are you sure?')) {

			jQuery(this).attr("disabled", true);

			var data = {
				'action': 'tks_update_products',
				'OK': 1
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(tks_ajax_object.ajax_url, data, function(response) {
				alert( response);
				jQuery(".update_products").attr("disabled", false);
			});
		}

	});


});