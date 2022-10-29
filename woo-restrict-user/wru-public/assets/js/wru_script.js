(function( jQuery ) {
	jQuery(function() {
		var url = MyAutocomplete.url + "?action=my_search";
		jQuery( "#search_user" ).autocomplete({
			source: url,
			delay: 0,
			minLength: 3,
		/*	select: function (event, ui) {
                var myVal = ui.item.value;
                var id = myVal.substr ( myVal.indexOf ( '#' ) + 1 );
                localStorage.setItem("userid", id);
                
            }*/
		
		});	

	//jQuery("#approve").click(function() {
	jQuery('body').on('click', '#approve', function() {
		var user = jQuery("#search_user").val();
		var post_id = jQuery("#search_user").attr("post-id");
		var select_access_date = jQuery("#select_access_date").val();
		var select_variaton = jQuery("#select_variaton").val();
		localStorage.getItem("userid");
		//console.log(select_variaton);	
		if(user==""){
			alert("Search User Field is required.");
			return false;
		}

		if(select_access_date==""){
			alert("Search User Field is required.");
			return false;
		}

			var data = {
				'action': 'wru_approve_user_product',
				'user': user,
				'post_id':post_id,
				'access_date':select_access_date,
				'select_variaton':select_variaton
			};
			jQuery.post(MyAutocomplete.url, data, function(response) {
				jQuery('#UserListing tr:last').after('<tr style="background:red;"><td colspan="4">User: ('+response+') added in product approve list successfully.</td></tr>');
				jQuery("User Approved");
				//console.log(response);
			});
	});

	jQuery('body').on('click', '.dlt_button', function(e) {
		e.preventDefault();
	 //var confirm = confirm('Are you sure?');
  	if (confirm('Are you sure?')) {
		var post_id = jQuery(this).attr("id");
		var user_id = jQuery(this).attr("user-id");
		var approve_date = jQuery(this).attr("approve-date");
		var var_list = jQuery(this).attr("variations");
		
		var data = {
			'action': 'wru_delete_approved_user',
			'user_id': user_id,
			'post_id':post_id,
			'approve_date':approve_date,
			'var_list':var_list,
		};
		jQuery.post(MyAutocomplete.url, data, function(response) {
			alert(response);
		});
	}

	});



	});

})( jQuery );

jQuery(document).ready(function(jQuery) {
	var dateToday = new Date(); 
    jQuery("#select_access_date").datepicker({
	numberOfMonths: 1,
	//showButtonPanel: true,
	minDate: dateToday,
    onSelect: function(date) {
        if(jQuery('#approve').length == 0) {
        	jQuery(".search_wrapper").append("<input type='button' id='approve' value='Approve' class='button button-primary'>");
    	}			
    }

    });

    //jQuery("#search_user_by_name_email").hide();


	/*jQuery('#request_consult').click(function() {
		if( jQuery(this).is(':checked')) {
			jQuery("#search_user_by_name_email").show();
		} else {
			jQuery("#search_user_by_name_email").hide();
		}
	}); 

	if( jQuery("#request_consult").is(':checked')) {
		jQuery("#search_user_by_name_email").show();
	} else {
		jQuery("#search_user_by_name_email").hide();
	}*/

});