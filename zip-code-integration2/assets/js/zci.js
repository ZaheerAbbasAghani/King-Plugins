jQuery(document).ready(function(){

    jQuery('#zci_zip_code_table').DataTable({
        "pageLength": 20
    });

    var i = 1;
    jQuery('#add').click(function(){
        i++;
        jQuery('#dynamic_field').append('<tr id="row'+i+'"><td><div class="field-wrap"><input type="text" name="zci_zip_codes[]" required placeholder="Enter Zip Code" style="width: 100%;"/></div></td></td><td><button name="remove" id="'+i+'" class="button button-default btn_remove">X</button></td></tr>');
    });

    jQuery(document).on('click','.btn_remove', function(){
        var button_id = jQuery(this).attr("id");
        jQuery("#row"+button_id+"").remove();
    });

    // Insert Records
    jQuery('#zci_submit').click(function(e){
        e.preventDefault();
        var data = {
            'action': 'zci_insert_zip_codes',
            'zipcodes': jQuery('#zci_zip_codes_form').serialize(),
        };

        jQuery.post(ajax_object.ajax_url, data, function(response) {
            jQuery("#zip_code_message").html(response);
            location.reload(true);
        });

    });


    // Edit Records
    jQuery('.zci_edit').click(function(e){
        e.preventDefault();
        jQuery("#edit_box").fadeIn();
        var edit_link = jQuery(this).attr("id");
        var edit_zip = jQuery(this).attr("zip_code");
        var value_zip = jQuery("#edit_zipcode").val(edit_zip);
        var value_id = jQuery("#edit_zipcode").attr("zip_code_id", edit_link);


    });

    // Update Records
    jQuery(".edit_now").click(function(e){
        e.preventDefault();
        var edit_zip_code = jQuery("#edit_zipcode").val();
        var edit_zip_id = jQuery("#edit_zipcode").attr("zip_code_id");
        var data = {
            'action': 'zci_edit_zip_codes',
            'edit_zip_code': edit_zip_code,
            'edit_zip_id': edit_zip_id,
        };

        jQuery.post(ajax_object.ajax_url, data, function(response) {
            alert(response);
            location.reload(true);
        });
    });

    // Delete Records
    jQuery(".zci_remove").click(function(e){
        e.preventDefault();
        var zip_id = jQuery(this).attr("id");
        //var edit_zip_id = jQuery("#edit_zipcode").attr("zip_code_id");
        var data = {
            'action': 'zci_delete_zip_codes',
            'zip_id': zip_id,
        };

        jQuery.post(ajax_object.ajax_url, data, function(response) {
            alert(response);
            location.reload(true);
        });
    });

    // Remove Box

    jQuery('#edit_box #cross').click(function(){
        jQuery("#edit_box").fadeOut();
    });


    // Search Zip Code Front-end

        


});