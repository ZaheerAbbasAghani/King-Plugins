jQuery(document).ready(function(){
	
	// Registry Process
	jQuery(document).on("click", ".fa-gift", function(){
		var pid = jQuery(this).attr("data-id");
		var data = {
			'action': 'gift_registry_process',
			'product_id': pid      // We pass php values differently!
		};
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post(gft_ajax_object.ajax_url, data, function(response) {

			if(response.status == 0){
				window.location.replace(response.redirect_url);
			}else if(response.status == 1){
				//alert(response.message);
				toastr.success(response.message);
			}else{
				toastr.warning(response.message);
			}

		});
	});


	// Registry Process
	jQuery(document).on("click", ".gwrap", function(){
		var pid = jQuery(this).find(".fa-gift").attr("data-id");
		var data = {
			'action': 'gift_registry_process',
			'product_id': pid      // We pass php values differently!
		};
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post(gft_ajax_object.ajax_url, data, function(response) {

			if(response.status == 0){
				window.location.replace(response.redirect_url);
			}else if(response.status == 1){
				//alert(response.message);
				toastr.success(response.message);
			}else{
				toastr.warning(response.message);
			}

		});
	});

	// Delete Registy Item

	jQuery(document).on("click", ".deleteProduct", function(e){

		e.preventDefault();

		if(confirm("Are you sure you want to delete this?")){
			var pid = jQuery(this).attr("data-id");

			jQuery(this).parent().parent().css("background","red");

			jQuery(this).parent().parent().remove();

			var data = {
				'action': 'gift_registry_item_delete',
				'product_id': pid      // We pass php values differently!
			};
			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			jQuery.post(gft_ajax_object.ajax_url, data, function(response) {
				toastr.warning(response);
				location.reload();
			});
		}else{
			return false;
		}
	});


	jQuery(".send_gift_registry_link").on("click", function(e) {
	    e.preventDefault();
		Swal.fire({
		  title: 'Gift Registry Receiver Info<hr>',
		  html: "<form method='post' action='' id='registry_form'> <input type='email' name='user_email' placeholder='Enter Receiver Email' required/> <br><br> <input type='tel' name='user_phone' placeholder='Enter Receiver Phone Number' required/><br><br> <textarea rows='5' cols='5' placeholder='Enter Your Message' name='your_message'></textarea> <input type='submit' value='Submit' class='submit'> </form>",
		  showConfirmButton: false,
		  allowOutsideClick: false,
		  showCloseButton: true,   
		});
	});

	// Send Gift Registry Link
	jQuery(document).on("submit", "#registry_form", function(e){
		e.preventDefault();

		jQuery(this).find("input[type='submit']").attr("disabled", true);
		var products = [];
		jQuery(".gift_registry_list table tbody tr").each(function(){
			if(jQuery(this).attr("data-id") != undefined){
				products.push(jQuery(this).attr("data-id"));
			}
		});

		var data = {
			'action': 'gift_registry_receiver_records',
			'formData': jQuery(this).serialize(),
			'products': products    

		};
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post(gft_ajax_object.ajax_url, data, function(response) {
			if(response.status == 1){
				toastr.success(response.message);
				Swal.close();
			}else{
				toastr.warning(response.message);
				Swal.close();
			}
		});

	});


/*	jQuery(document).on("click", ".purchaseIt", function(e){

			e.preventDefault();

			var pid = jQuery(this).attr("data-id");
			var url = jQuery(this).attr("href");

			//jQuery(this).parent().parent().remove();

			var data = {
				'action': 'gift_registry_product_purchasing',
				'product_id': pid,      // We pass php values differently!
				'product_url': url,
			};
			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			jQuery.post(gft_ajax_object.ajax_url, data, function(response) {
				//toastr.success(response);
				//location.reload();
				window.location.replace(response);
			});
		
	});
*/

	

});