jQuery(document).ready(function(){

	jQuery("#afi_get_info").submit(function(e){
		e.preventDefault();

	var txt = jQuery(this).find("input[type='text']").val();
	var attr = jQuery(this).find("input[type='text']").attr("name");
	//alert(txt);

	var data = {
	    'action': 'afi_update_field',
	    'field': txt,
	    'attr':attr,
	};

	jQuery.post(ajax_afi.ajax_url, data, function(response) {
		alert(response);
	    location.reload(true);
	});




	})

});