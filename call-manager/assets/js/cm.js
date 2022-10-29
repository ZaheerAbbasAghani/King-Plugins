jQuery(document).ready(function(){
	var phone = jQuery(".cm_phone_number").text();
	jQuery(".woocommerce-order-overview li:last").after('<li class="cm_phone_number_text" style="margin-top:20px;">Phone Number:<strong>'+phone+'</strong></li>');


	jQuery("#send_message").submit(function(e){
		e.preventDefault();
		jQuery(this).find("input[type='submit']").val("Sending...");
		var receiver_number = jQuery("#receiver_number").val();
		var message = jQuery("#message").val();

		var data = {
			'action': 'cm_send_messages',
			'receiver_number': receiver_number,
			'message':message
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(cm_object.ajax_url, data, function(response) {
			jQuery(this).find("input[type='submit']").val("Sent.");
			alert(response);
            location.reload();
		});


	});



});