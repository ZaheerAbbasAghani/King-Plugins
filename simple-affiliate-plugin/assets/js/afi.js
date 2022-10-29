jQuery(document).ready(function(){

	

	
	jQuery(".afi_get_info").submit(function(e){
		e.preventDefault();

	var txt = jQuery(this).find("input[type='text']").val();
	var attr = jQuery(this).find("input[type='text']").attr("name");
	
	var msg_id = jQuery(this).parent().find("div").attr("id");

	var data = {
	    'action': 'afi_update_field',
	    'field': txt,
	    'attr':attr,
		'msg_id':msg_id,
	};

	jQuery.post(ajax_afi.ajax_url, data, function(response) {
		//console.log(response);
		jQuery("#"+response.id).before("<p>"+txt+"</p>");
		jQuery("#"+response.id).html("<p>"+response.message+"</p>");
	    location.reload(true);
	});

	});


});