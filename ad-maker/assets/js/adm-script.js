jQuery(document).ready(function(){


	jQuery(document).on("submit","#adm_user_response", function(e){
		e.preventDefault();
		//	jQuery(this).find("#submit-button").attr("disabled", true);
			var data = {
				'action': 'adm_submit_form',
				'async': true,
				'formData': jQuery(this).serialize()

			};
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(adm_ajax_object.ajax_url, data, function(response) {
				//console.log(response);
				if(response.status == 1){
					toastr.success(response.message);
					location.reload();
				}else{
					//jQuery("#submit-button").attr("disabled", false);
					toastr.error(response.message);
				}
			});
	});



	var params = new window.URLSearchParams(window.location.search);

	if(params.has('adid')){
	//	if (confirm('Are you sure you want to delete your AD?')) {
			
			var data = {
				'action': 'adm_delete_ad',
				'id': params.get('adid'),
			};
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(adm_ajax_object.ajax_url, data, function(response) {
				//console.log(response);
				toastr.info(response);
				window.location.replace('/');
			});

		/*} else {
			window.location.replace('/');
		}*/
	}

	jQuery('#example').DataTable();

	/*jQuery(".phone").keyup(function() {
	    jQuery(this).val(jQuery(this).val().replace(/^(\d{3})(\d{3})(\d+)jQuery/, "(jQuery1)jQuery2-jQuery3"));
	});*/


	/*jQuery('.phone').keyup(function(e) {

		if(jQuery(this).val().length == 14){
	  	jQuery(this).attr("readonly",true);
	  }

	  if ((e.keyCode > 47 && e.keyCode < 58) || (e.keyCode < 106 && e.keyCode > 95)) {
	    this.value = this.value.replace(/(\d{3})\-?/g, 'jQuery1-');
	    return true;
	  }
	  
	  this.value = this.value.replace(/[^\-0-9]/g, '');
	});*/


	jQuery('.phone').keyup(function() {
		var curchr = this.value.length;
		var curval = jQuery(this).val();
		if (curchr == 3) {
			jQuery('.phone').val("(" + curval + ")" + "-");
		} else if (curchr == 9) {
			jQuery('.phone').val(curval + "-");
		}
	});

	var today = new Date().toISOString().split('T')[0];
	jQuery("input[type='date']")[0].setAttribute('min', today);


});