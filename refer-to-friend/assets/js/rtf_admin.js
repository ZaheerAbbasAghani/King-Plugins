jQuery(document).ready(function(){

	// Upload an Image
	var custom_uploader
	  , click_elem = jQuery('.rtf_upload_logo')
	  , target = jQuery('input[name="rtf_upload_logo_url"]')

	click_elem.click(function(e) {
	    e.preventDefault();
	    //If the uploader object has already been created, reopen the dialog
	    if (custom_uploader) {
	        custom_uploader.open();
	        return;
	    }
	    //Extend the wp.media object
	    custom_uploader = wp.media.frames.file_frame = wp.media({
	        title: 'Choose Image',
	        button: {
	            text: 'Choose Image'
	        },
	        multiple: false
	    });
	    //When a file is selected, grab the URL and set it as the text field's value
	    custom_uploader.on('select', function() {
	        attachment = custom_uploader.state().get('selection').first().toJSON();
	        target.val(attachment.url);
	    });
	    //Open the uploader dialog
	    custom_uploader.open();
	});      

	// Color Picker

    jQuery('.my-color-field').wpColorPicker({
    	change: function(event, ui){
    		jQuery("#rtf_background_color").val(ui.color.toString());
    	},
    });

    jQuery('.my-color-field-2').wpColorPicker({
    	change: function(event, ui){
    		jQuery("#rtf_text_color").val(ui.color.toString());
    	},
    });

    jQuery('.my-color-field-3').wpColorPicker({
    	change: function(event, ui){
    		jQuery("#rtf_field_text_color").val(ui.color.toString());
    	},
    });


    jQuery('.my-color-field-3').wpColorPicker({
    	change: function(event, ui){
    		jQuery("#rtf_field_text_color").val(ui.color.toString());
    	},
    });

    jQuery('.my-color-field-4').wpColorPicker({
    	change: function(event, ui){
    		jQuery("#rtf_button_background").val(ui.color.toString());
    	},
    });



// Add Field
jQuery(document).on('click','#add_field',function(e){

	e.preventDefault();
	jQuery(this).text("Loading...");
   	var data = {
		'action': 'rtf_create_form_new_field',
		 dataType: 'json',
		//'customer_info':jQuery(this).serialize()
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(rtf_ajax_admin_object.ajax_url, data, function(response) {
		

		Swal.fire({
		  	title: "Create a field <hr>", 
		  	html: response,  
		 	showCancelButton: false, allowOutsideClick: false,
		 	showConfirmButton: false,
		 	showCloseButton: true,
			}).then(function(){ 
				location.reload();
		   }
		);

	});
});



// Create Field
jQuery(document).on('click','#create_all_fields',function(e){

	e.preventDefault();
	jQuery(this).val("Loading...");

	var flabel	=	jQuery(this).parent().find("#field_label").val();
	var ftype	=	jQuery(this).parent().find("#field_type").val();
	var fvalue  =	jQuery(this).parent().find("#dropdownValues").val() ? jQuery(this).parent().find("#dropdownValues").val() : "";
	var fplaceholder = jQuery(this).parent().find("#field_placeholder").val();


   	var data = {
		'action': 'rtf_insert_field',
		 dataType: 'json',
		'flabel':flabel,
		'ftype':ftype,
		'fvalue':fvalue,
		'field_placeholder':fplaceholder
	};
	

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(rtf_ajax_admin_object.ajax_url, data, function(response) {
		alert(response);
		location.reload();
	});
});



//Edit a Field
jQuery('body').on('click', '.edit_field', function(e) {
	e.preventDefault();
	var id = jQuery(this).attr("data-id");

	var data = {
		'action': 'rtf_edit_field',
		'id':id,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(rtf_ajax_admin_object.ajax_url, data, function(response) {

		Swal.fire({
		  	title: "Edit Field <hr>", 
		  	html: response,  
		 	showCancelButton: false, allowOutsideClick: false,
		 	showConfirmButton: false, showCloseButton: true,
		});
	});
});


// Delete Sickness
jQuery(document).on('click','.delete_field',function(e){
	e.preventDefault();

	if (confirm('Are you sure?')) {
		jQuery(this).text("Loading...");

		var id=jQuery(this).attr("data-id");
		var column=jQuery(this).attr("data-column");

	   	var data = {
			'action': 'rtf_delete_field',
			 dataType: 'json',
			'id':id,
			'col':column
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(rtf_ajax_admin_object.ajax_url, data, function(response) {
			alert(response);
			location.reload();
		});

	}else{
		return false;
	}
});


jQuery(".rtf_accordion").each(function(k,v){
	jQuery(this).accordion({
  		collapsible: true, active: false, heightStyle: "content"
	});

	jQuery(this).find("table").DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'pdf', 'print'
        ],
       
    } );
})


jQuery(".preload").fadeOut(2000, function() {
    jQuery(".page2wrap").fadeIn(1000);        
});


jQuery(document).on("change", "#field_type", function(){
	if(jQuery(this).val() == "select"){

		jQuery(this).parent("label").after('<label> Field Values: <textarea id="dropdownValues" placeholder="Add comma seperated values: fashion,sports,shopping" rows="5"></textarea></label>');
	}
	return false;
});


jQuery(".anamnese_settings").sortable({
    delay: 150,
    stop: function() {
        var selectedData = new Array();
        jQuery('.anamnese_settings ul').each(function() {
            selectedData.push(jQuery(this).attr("id"));
        });
        updateOrder(selectedData);
    }
});
function updateOrder(data) {
	var data = {
		'action': 'rtf_drag_drop_fields',
		 data:data,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(rtf_ajax_admin_object.ajax_url, data, function(response) {
		console.log(response);
	});
}



});