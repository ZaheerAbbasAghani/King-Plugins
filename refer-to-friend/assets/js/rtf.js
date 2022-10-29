jQuery(document).ready(function(){

	// Refer a friend
	jQuery(document).on("click",".rtf_refer_to_friend_btn", function(e){
		e.preventDefault();

		jQuery(this).text("Loading...");
	   	var data = {
			'action': 'rtf_create_new_entry',
			 dataType: 'json',
			'page_id': rtf_ajax_object.page_id
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(rtf_ajax_object.ajax_url, data, function(response) {
			

			Swal.fire({
			  	html: response,  
			 	showCancelButton: false, allowOutsideClick: false,
			 	showConfirmButton: false,
			 	showCloseButton: true,
			 	width: rtf_ajax_object.popup_width,
			});

		});

		//jQuery(".swal2-popup").css("opacity",rtf_ajax_object.opacity);

	});


/*
	Insert Entry.
*/

jQuery('body').on('submit', '#refer_form', function(e){
	e.preventDefault();
	jQuery("#refer_form .button").val("Loading...");
	jQuery("#refer_form .button").attr("disabled", true);
	
	var data = {
		'action': 'rtf_insert_new_entry',
	//	 dataType: 'json',
		'message_info':jQuery(this).serialize()
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(rtf_ajax_object.ajax_url, data, function(response) {
	//	console.log(response);
		if(response.status == 1){
			Swal.fire({
			  	title: "Success<hr>", 
			  	html: response.message,  
			 	showCancelButton: false, allowOutsideClick: false,
			 	showConfirmButton: true,
			 	icon:'success',
		 	}).then(function(){ 
					location.reload();
			   }
			);
		}else{
			Swal.fire({
			  	title: "Fail<hr>", 
			  	html: response.message,  
			 	showCancelButton: false, allowOutsideClick: false,
			 	showConfirmButton: true,
			 	icon: 'error',
		 	}).then(function(){ 
					location.reload();
			   }
			);
		}
	});
});




});