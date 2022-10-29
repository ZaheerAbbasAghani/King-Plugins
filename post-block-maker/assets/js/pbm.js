jQuery(document).ready(function(){

	jQuery("#blockGenerator").click(function(e){

		e.preventDefault();

		jQuery(this).find("input[type='button']").attr("disabled", true);

		var page = jQuery('#pbm_listPages').val();
		var category = jQuery('#pbm_listCategory').val();
		//alert(page);
		var data = {
			'action': 'pbm_create_post_blocks',
			'page': page,
			'category':category,
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(pbm_ajax_object.ajax_url, data, function(response) {
			console.log(response);
			jQuery("#blockGeneratorForm").submit();
		});


	});

});