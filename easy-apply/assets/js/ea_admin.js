jQuery(document).ready(function(){
	// Datatable
	jQuery('#apply_information').DataTable();


// Delete Applied for
jQuery(document).on('click','.delete_record',function(e){
//jQuery(".delete_customer a").click(function(e){
	e.preventDefault();
	if( !confirm('Are you sure?')) {
		return false;
	}else{
		
		jQuery(this).parent().parent().css({"color":"red", "opacity":"0.5"});
		var id = jQuery(this).attr("data-id");
		var data = {
			'action': 'ea_delete_appliedfor',
			'id':id
		};
	}
	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ae_object.ajax_url, data, function(response) {
		 alert(response);
		 location.reload();
	});

});



});