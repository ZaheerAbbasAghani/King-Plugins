jQuery(document).ready(function(){

    jQuery('#zci_br_relation_table').DataTable({
        "pageLength": 20
    });

   var i = 1;
    jQuery('#add').click(function(){
        i++;
        jQuery('#dynamic_field').append('<tr id="row'+i+'"><td><div class="field-wrap"><input type="text" name="zci_br_relations[]" required placeholder="Enter Relationship" style="width: 100%;"/></div></td></td><td><button name="remove" id="'+i+'" class="button button-default btn_remove">X</button></td></tr>');
    });

    jQuery(document).on('click','.btn_remove', function(){
        var button_id = jQuery(this).attr("id");
        jQuery("#row"+button_id+"").remove();
    });

    // Insert Records
    jQuery('#br_submit').click(function(e){
        e.preventDefault();
        var data = {
            'action': 'br_insert_relationship',
            'relation': jQuery('#zci_br_relations_form').serializeArray(),
        };

        jQuery.post(ajax_object.ajax_url, data, function(response) {
        	alert(response);
            location.reload(true);
        });

    });

     // Delete Records
    jQuery(".zci_remove").click(function(e){
        e.preventDefault();

    var confirmText = "Are you sure?";
    if(confirm(confirmText)) {

        var reltion = jQuery(this).attr("id");
        //var edit_zip_id = jQuery("#edit_zipcode").attr("zip_code_id");
        var data = {
            'action': 'br_delete_relations',
            'reltion': reltion,
        };

        jQuery.post(ajax_object.ajax_url, data, function(response) {
            alert(response);
            location.reload(true);
        });

    }

    });




/*var myOptions = {
    val1 : 'Blue',
    val2 : 'Orange'
};
var mySelect = $('#myColors');
$.each(myOptions, function(val, text) {
    mySelect.append(
        $('<option></option>').val(val).html(text)
    );
});
*/


});