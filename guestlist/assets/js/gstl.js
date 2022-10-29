jQuery(document).ready(function(){
	jQuery( "#gstl_accordion" ).accordion({ 
		header: "h3", 
		collapsible: true, 
		active: false 
	});

jQuery(document).on("keydown", "form", function(event) { 
    return event.key != "Enter";
});


	  // Add members in list

    jQuery(".btn_insert").click(function(e){
        e.preventDefault();
        if(confirm("Are you sure?")){

            var member = jQuery(this).parent().find("#insert_members").val();
            var post_id = jQuery(this).parent().find(".post_id").val();
        
            var data = {
                'action': 'gstl_add_members_in_list',
                'post_id':post_id,
                'member_name':member
            };
            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            jQuery.post(gstl_object.ajax_url, data, function(response) {
                alert(response);
                location.reload();
            });
        }
        else{
            return false;
        }
    });

     // Add members in list

    jQuery(".gstl_member_delete").click(function(e){
        e.preventDefault();

        if(confirm("Are you sure?")){

            var id = jQuery(this).attr("member-id");
            var post_id = jQuery(this).attr("post-id");
            var data = {
                'action': 'gstl_remove_members_in_list',
                'post_id':post_id,
                'member_id':id
            };
            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            jQuery.post(gstl_object.ajax_url, data, function(response) {
                alert(response);
                location.reload();
            });
        }
        else{
            return false;
        }
    });


});