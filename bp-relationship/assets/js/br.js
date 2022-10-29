jQuery(document).ready(function(){

// Relationship Request Submit	
jQuery('#submit_relation').click(function(){ 
	var username = jQuery(".yz-head-content .yz-name h2").text();
	var confirmText = "Profile user is your "+jQuery(".relations_form .nice-select span.current").text();

    if(confirm(confirmText)) {
    	var relation = jQuery(".relations_form .nice-select span.current").text();
    	var user_id = jQuery(".yz-profile-header .yz-tools").attr("data-user-id");
    	
    	var data = {
			'action': 'choose_relation_with_user',
			'relation': relation,
			'user_name': jQuery("#list_relation").val()
		};
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post(ajax_object.ajax_url, data, function(response) {
			alert(response);
			location.reload();
		});
    }

});

// Friends Autocomplete
jQuery( function() {
	var availableTags = JSON.parse(ajax_object.rela_array);
	jQuery( "#list_relation" ).autocomplete({
	  source: availableTags
	});
});

//relationship confirmation
jQuery(".br_yes").click(function(){
	var sender = jQuery(this).parent().find("p").attr("relate");
	var data = {
		'action': 'br_confirm_yes',
		'sender': sender,
	};
	jQuery.post(ajax_object.ajax_url, data, function(response) {
		alert(response);
		location.reload();
	});
})

//Showing 2 persons relationship
var relative = JSON.parse(ajax_object.relatives);
//alert(relative);
if(relative !== "[]" ){

	jQuery.each(relative, function(i, item) {
		console.log(item.relation);
	    jQuery(".yz-head-content .yz-name").after("<span class='my_relative'> "+item.relation+" with "+item.display_name+"</span>");
	});


}



});