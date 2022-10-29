jQuery(document).ready(function(){


	jQuery(".puzzle_form").submit(function(e) {
	e.preventDefault();

	var answer1 = jQuery(this).find(".answer1").val();
	var answer2 = jQuery(this).find(".answer2").val();
	var answer3 = jQuery(this).find(".answer3").val();
	var puzzle_number = jQuery(this).find("#puzzle_number").val();
	var id = jQuery(this).parent(".prox_box").attr("id");

		jQuery(this).find(".submit_puzzle").val("Loading...");
	
	if (localStorage.getItem("attempt_"+id) != "attempt_"+id) {
    	var attempt = localStorage.setItem("attempt_"+id, "attempt_"+id);
    	var val=1;
	}else{
		var val=0;
	}

	var data = {
		'action': 'search_values_in_puzzles',
		'dataType': "json",
		'post_id': id,
		'answer1':answer1,
		'answer2':answer2,
		'answer3':answer3,
		'puzzle_number':puzzle_number,
		'attempt':val
	};

	jQuery.post(ajax_object.ajax_url, data, function(response) {
	
		if(response.status == "fail"){
			var parts = ajax_object.fail_url;
			var last_part=parts.substr(parts.lastIndexOf('/') + 1);		
			jQuery("#"+id).find("form").append("<p style='color:red;'>"+response.message+"</p>");
	
			window.location.replace(last_part);

		}if(response.status == "late"){
			var parts = response.redirect_url;
			var endd = parts[parts.length-2];
			jQuery("#"+id).find("form").append("<p style='color:blue;'>"+response.message+"</p>");
			window.location.replace(endd);
		
		}
		if(response.status == "success"){
			var parts = response.redirect_url;
			var endd = parts[parts.length-2];
			jQuery("#"+id).find("form").append("<p style='color:green;'>"+response.message+"</p>");
			window.location.replace(endd);
		}
		jQuery(".submit_puzzle").val("Submit");
	});
	

});


});