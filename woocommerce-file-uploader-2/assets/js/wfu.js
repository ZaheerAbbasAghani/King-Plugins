jQuery(document).ready(function(){


jQuery(document).on('change', '#file', function() {


	file_obj = jQuery(this).prop('files');
	form_data = new FormData();

	if(file_obj.length > 2){
		alert("Upload only 2 images");
		 jQuery(this).val(''); 
		return false;
	}
	jQuery(".single_add_to_cart_button").attr("disabled", true);
	for(i=0; i<file_obj.length; i++) {
	    form_data.append('file[]', file_obj[i]);
	}
	
	var attr = jQuery(".single_add_to_cart_button").attr('value');
	if (typeof attr !== 'undefined' && attr !== false) {
		form_data.append('product_id',jQuery(".single_add_to_cart_button").attr("value"));
	}else{
		form_data.append('product_id', jQuery("input[name='product_id']").val());
		alert(jQuery("input[name='product_id']").val());		
	}
	form_data.append('action', 'wfu_file_upload');
	form_data.append('security', wfu_object.security);

	jQuery.ajax({
	    url: wfu_object.ajaxurl,
	    type: 'POST',
	    contentType: false,
	    processData: false,
	    data: form_data,
	    success: function (response) {
			console.log(response);
	        jQuery(".single_add_to_cart_button").attr("disabled", false);
	    }
	});

});


jQuery(document).on('click', '.product-remove', function() {
	//alert();
		var product_id = jQuery(this).find("a").attr("data-product_id");

		var data = {
			'action': 'wfu_remove_product_images',
			'product_id': product_id
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(wfu_object.ajaxurl, data, function(response) {
			console.log(response);
		});


/*	jQuery.ajax({
		action: "wfu_remove_product_images",
	    url: wfu_object.ajaxurl,
	    type: 'POST',
	    data: product_id,
	    success: function (response) {
			console.log(response);
	      //  jQuery(".single_add_to_cart_button").attr("disabled", false);
	    }
	});*/

});



});