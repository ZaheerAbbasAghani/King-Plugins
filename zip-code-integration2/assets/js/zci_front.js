jQuery(document).ready(function(){

if (localStorage.getItem("availability") === null) {
 	console.log("");
}else{
	jQuery(".check_availability").remove();
}


jQuery('.zci_search').prop('disabled', true);

jQuery('.zci_search_box').on('keyup', function() {

	var btn = jQuery('.zci_search');

	if (jQuery(this).val().length == 5) {

		btn.prop('disabled', false);

		btn.css("opacity",1);

		btn.css("cursor","pointer");

	} else {

		btn.prop('disabled', true);

	}

});





	jQuery(".zci_search").click(function(e){

		e.preventDefault();

		var zip_code = jQuery(".zci_search_box").val();

		jQuery(this).val("Loading...");
		
		var data = {
            'action': 'zci_search_zip_codes',
            'zip_code': zip_code,
        };

        jQuery.post(ajax_object.ajax_url, data, function(response) {
        	console.log(response.alert);
        	if(response.alert == "fail"){
        		jQuery("#zci_message").html("<p style='background:#eee;padding: 10px;margin-top: 20px;text-align: center;'>"+response.message+"</p>");
        			jQuery(".zci_search").val("Check Availability");
        	}else{
        		jQuery("#zci_message").html("<p style='background:#eee;padding: 10px;margin-top: 20px;text-align: center;'>"+response.message+"</p>");
        		jQuery(".zci_search").val("Check Availability");
        		localStorage.setItem("availability", "1");
        		location.reload();
        	}
       		
        });

	});	



});