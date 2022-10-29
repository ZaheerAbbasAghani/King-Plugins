jQuery(document).ready(function(){

    jQuery('table.display').DataTable();

	var i = 1;
	jQuery('#add').click(function(){
	    i++;
	    jQuery('#dynamic_field').append('<tr id="row'+i+'"><td><div class="field-wrap"><input type="text" name="afi_field_name[]" required placeholder="Enter Field Name" style="width: 100%;"/></div></td></td><td><button name="remove" id="'+i+'" class="button button-default btn_remove">X</button></td></tr>');
	});

	jQuery(document).on('click','.btn_remove', function(){
        var button_id = jQuery(this).attr("id");
        jQuery("#row"+button_id+"").remove();
    });


    // Insert Fields
    jQuery('#afi_submit').click(function(e){
        e.preventDefault();
        var data = {
            'action': 'afi_insert_fields',
            'fields': jQuery('#afi_field_name_form').serialize(),
        };

        jQuery.post(ajax_afi.ajax_url, data, function(response) {
        //	console.log(response);
            jQuery("#field_code_message").html(response);
            location.reload(true);
        });

    });


    // Delete Records
    jQuery(".afi_remove").click(function(e){
        e.preventDefault();
        var field = jQuery(this).attr("id");
        var field_name = jQuery(this).attr("field_name");
        //var edit_zip_id = jQuery("#edit_zipcode").attr("zip_code_id");
        var data = {
            'action': 'afi_delete_fields',
            'field': field,
            'field_name':field_name,
        };

        jQuery.post(ajax_afi.ajax_url, data, function(response) {
            alert(response);
            location.reload(true);
        });
    });
	
	jQuery(".afifield").on("keydown", function(e){
        if(e.keyCode == 32){
           alert("Use dash or underscore no space allowed.");
			var trimStr = jQuery.trim(jQuery(this).val());
		   	jQuery(this).val("");
        }
	});

});