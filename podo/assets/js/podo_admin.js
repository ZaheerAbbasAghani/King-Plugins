jQuery(document).ready(function(){

// Add Sickness
jQuery(document).on('click','#add_sickness',function(e){

	e.preventDefault();
	jQuery(this).text("Wird geladen...");
   	var data = {
		'action': 'podo_create_anamnese_new_field',
		 dataType: 'json',
		//'customer_info':jQuery(this).serialize()
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {
		

		Swal.fire({
		  	title: "Eintrag hinzufügen <hr>", 
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


// Choose field to add in form

jQuery(document).on('change','#field_type',function() {    
	var flabel = jQuery("#podo_field_maker #field_label").val();
	var trimStr = flabel.replace(/\s/g, '_').toLowerCase();

	if(flabel == ""){
		alert("Fügen Sie eine Beschriftung für das Feld ein");
		return false;
	}
});	


// Create Sickness
jQuery(document).on('click','#create_all_fields',function(e){

	e.preventDefault();
	jQuery(this).text("Wird geladen...");

	var flabel=jQuery(this).parent().find("#field_label").val();
	var ftype=jQuery(this).parent().find("#field_type").val();

   	var data = {
		'action': 'podo_insert_field',
		 dataType: 'json',
		'flabel':flabel,
		'ftype':ftype
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {
		alert(response);
		location.reload();
	});
});


// Delete Sickness
jQuery(document).on('click','.delete_field',function(e){
	e.preventDefault();

	if (confirm('Eintrag wirklich löschen?')) {
		jQuery(this).text("Wird geladen...");

		var id=jQuery(this).attr("data-id");
		var column=jQuery(this).attr("data-column");

	   	var data = {
			'action': 'podo_delete_field',
			 dataType: 'json',
			'id':id,
			'col':column
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(podo_object.ajax_url, data, function(response) {
			alert(response);
			location.reload();
		});

	}else{
		return false;
	}
});


//Edit a Field
jQuery('body').on('click', '.edit_field', function(e) {
	e.preventDefault();
	var id = jQuery(this).attr("data-id");

	var data = {
		'action': 'podo_edit_customer',
		'id':id,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {

		Swal.fire({
		  	title: "Feld bearbeiten <hr>", 
		  	html: response,  
		 	showCancelButton: false, allowOutsideClick: false,
		 	showConfirmButton: false, showCloseButton: true,
	 		width: '800px'	
		});

	});

});


// Add Sickness
jQuery(document).on('click','#update_all_fields',function(e){

	e.preventDefault();
	jQuery(this).text("Wird geladen...");

	var flabel=jQuery(this).parent().find("#field_label").val();
	var ftype=jQuery(this).parent().find("#field_type").val();
	var field_id=jQuery(this).parent().find("#field_id").val();

   	var data = {
		'action': 'podo_update_field',
		 dataType: 'json',
		'flabel':flabel,
		'ftype':ftype,
		'field_id':field_id
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {
		alert(response);
		location.reload();
	});
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
		'action': 'podo_drag_drop_fields',
		data:data,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {
		alert(response);
	});
}


// Add Treatment
jQuery(document).on('click','#create_all_treatments',function(e){

	e.preventDefault();
	jQuery(this).text("Wird geladen...");

	var tname=jQuery(this).parent().find("#treatment_name").val();
	var tprice=jQuery(this).parent().find("#treatment_price").val();
	var tduration=jQuery(this).parent().find("#treatment_duration").val();
	var tdescription=jQuery(this).parent().find("#treatment_description").val();

   	var data = {
		'action': 'podo_insert_treatments',
		 dataType: 'json',
		'tname':tname,
		'tprice':tprice,
		'tduration':tduration,
		'tdescription':tdescription
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {
		alert(response);
		location.reload();
	});
});


// Update Treatment
jQuery(document).on('click','#update_all_treatments',function(e){

	e.preventDefault();
	jQuery(this).text("Wird geladen...");

	var name =jQuery(this).parent().find("#treatment_name").val();
	var price =jQuery(this).parent().find("#treatment_price").val();
	var duration=jQuery(this).parent().find("#treatment_duration").val();
	var description=jQuery(this).parent().find("#treatment_description").val();
	var field_id=jQuery(this).parent().find("#field_id").val();

   	var data = {
		'action': 'podo_update_treatment',
		 dataType: 'json',
		'name':name,
		'price':price,
		'duration':duration,
		'description':description,
		'field_id':field_id
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {
		alert(response);
		location.reload();
	});
});


// Delete Treatment
jQuery(document).on('click','.delete_treatment',function(e){
	e.preventDefault();

	if (confirm('Eintrag wirklich löschen?')) {
		jQuery(this).text("Wird geladen...");

		var id=jQuery(this).attr("data-id");
		
	   	var data = {
			'action': 'podo_delete_treatment',
			 dataType: 'json',
			'id':id,
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(podo_object.ajax_url, data, function(response) {
			alert(response);
			location.reload();
		});

	}else{
		return false;
	}
});


var custom_uploader , click_elem = jQuery('.podo-file-upload') , target = jQuery('#podo_firm input[name="podo_company_logo"]')

click_elem.click(function(e) {
  e.preventDefault();
  //If the uploader object has already been created, reopen the dialog
  if (custom_uploader) {
      custom_uploader.open();
      return;
  }
  //Extend the wp.media object
  custom_uploader = wp.media.frames.file_frame = wp.media({
      title: 'Bild auswählen',
      button: {
          text: 'Bild auswählen'
      },
      multiple: false
  });
  //When a file is selected, grab the URL and set it as the text field's value
  custom_uploader.on('select', function() {
      attachment = custom_uploader.state().get('selection').first().toJSON();
      target.val(attachment.url);
      jQuery("#preview_logo").attr("src",attachment.url);
  });
  //Open the uploader dialog
  custom_uploader.open();
});      


// Datatable
jQuery('#customer_list').DataTable();

//Edit a Customer
jQuery('body').on('click', '.edit_customer_dashboard', function(e) {
	e.preventDefault();
	var id = jQuery(this).attr("data-id");

	var data = {
		'action': 'podo_edit_customer_dashboard',
		'id':id,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {

		Swal.fire({
		  	title: "Kunde bearbeiten <hr>", 
		  	html: response,  
		 	showCancelButton: false, allowOutsideClick: false,
		 	showConfirmButton: false, showCloseButton: true,
	 		width: '1200px'	
		});

	});

});


// Update Customer
jQuery(document).on('submit','#update_new_customer_dashboard',function(e){

	e.preventDefault();
	jQuery("#update_new_customer .button").val("Wird geladen...");
	var id = jQuery("#cid").val();
	localStorage.setItem('selected_customer', id);
   	
   	var data = {
		'action': 'anam_update_customer',
		 dataType: 'json',
		'customer_info':jQuery(this).serialize()
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {

		if(response.status == 1){
			Swal.fire({
			  	title: "Erfolgreich<hr>", 
			  	html: response.message,  
			 	showCancelButton: false, allowOutsideClick: false,
			 	showConfirmButton: true,
			 	icon:'success',
		 	}).then(function(){ 
					location.reload();
			   }
			);
		}else{
			Swal.fire({
			  	title: "Fehler!<hr>", 
			  	html: response.message,  
			 	showCancelButton: false, allowOutsideClick: false,
			 	showConfirmButton: true,
			 	icon: 'error',
		 	}).then(function(){ 
					location.reload();
			   }
			);
		}

	});
});


//Add payments
jQuery('body').on('click', '.add_payment_method', function(e) {
	e.preventDefault();

	var data = {
		'action': 'podo_add_payment',
		'payment':1,
		dataType: 'json',
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {

		Swal.fire({
		  	title: "Zahlung hinzufügen <hr>", 
		  	html: response,  
		 	showCancelButton: false, allowOutsideClick: false,
		 	showConfirmButton: false, showCloseButton: true,
	 		width: '800px'	
		});

	});

});

jQuery('body').on('click', '#payment_method_image', function(e) {
//jQuery('#payment_method_image').click(function(e) {
    e.preventDefault();
    var custom_uploader = wp.media({
        title: 'Benutzerdefiniertes Bild',
        button: {
            text: 'Bild hochladen'
        },
        multiple: false  // Set this to true to allow multiple files to be selected
    })
    .on('select', function() {
        var attachment = custom_uploader.state().get('selection').first().toJSON();
        //console.log(attachment.url);
        jQuery("#QRImage").val(attachment.url);
        jQuery("#payImageDisplay").attr("src",attachment.url);

    })
    .open();
});

jQuery('body').on('click', '.UploadQR', function(e) {
    e.preventDefault();
    var id = jQuery(this).attr("data-id");
    localStorage.setItem('up', id);

    var custom_uploader = wp.media({
        title: 'Benutzerdefiniertes Bild',
        button: {
            text: 'Bild hochladen'
        },
        multiple: false  // Set this to true to allow multiple files to be selected
    })
    .on('select', function() {
        var attachment = custom_uploader.state().get('selection').first().toJSON();
        
        var id = localStorage.getItem('up');
        var data = {
			'action': 'podo_upload_qr_image',
			'image':attachment.url,
			'dataType': 'json',
			'id':id
		};

		jQuery.post(podo_object.ajax_url, data, function(response) {
//			console.log(response);
			if(response.status == 1){
				Swal.fire({
				  	title: "Zahlungsmethoden <hr>", 
				  	html: response.message,  
				 	showCancelButton: false, allowOutsideClick: false,
				 	showConfirmButton: true,
				 	icon:'success',
			 	}).then(function(){ 
						location.reload();
				   }
				);
			}else{
				Swal.fire({
				  	title: "Fehler!<hr>", 
				  	html: response.message,  
				 	showCancelButton: false, allowOutsideClick: false,
				 	showConfirmButton: true,
				 	icon: 'error',
			 	}).then(function(){ 
						location.reload();
				   }
				);
			}

		});



    })
    .open();
});


// Add Payment
jQuery(document).on('submit','#payment_information',function(e){

	e.preventDefault();
	
   	var data = {
		'action': 'podo_insert_payments',
		 dataType: 'json',
		'payments':jQuery(this).serialize()
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {
		//console.log(response);
		if(response.status == 1){
			Swal.fire({
			  	title: "Bezahlverfahren <hr>", 
			  	html: response.message,  
			 	showCancelButton: false, allowOutsideClick: false,
			 	showConfirmButton: true,
			 	icon:'success',
		 	}).then(function(){ 
					location.reload();
			   }
			);
		}else{
			Swal.fire({
			  	title: "Fehler!<hr>", 
			  	html: response.message,  
			 	showCancelButton: false, allowOutsideClick: false,
			 	showConfirmButton: true,
			 	icon: 'error',
		 	}).then(function(){ 
					location.reload();
			   }
			);
		}

	});
});


// Delete Payment Method
jQuery(document).on('click','.delete_payment_method',function(e){
	e.preventDefault();

	if (confirm('Eintrag wirklich löschen?')) {
		jQuery(this).text("Wird geladen...");
		jQuery(this).parent().parent().css("background","red");
		var id=jQuery(this).attr("data-id");
		
	   	var data = {
			'action': 'podo_delete_payment_method',
			 dataType: 'json',
			'id':id,
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(podo_object.ajax_url, data, function(response) {
			alert(response);
			location.reload();
		});

	}else{
		return false;
	}
});


// jQuery('#tabs').tabs();

jQuery( function() {
	jQuery( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
	jQuery( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
});

jQuery(document).on('click','.change_status',function(e){
	e.preventDefault();
	if(confirm("Eintrag wirklich löschen you want to change status?")){
        var id = jQuery(this).attr("data-id");
       	jQuery(this).parent().parent().css("background","#ff000094");
       	jQuery(this).parent().parent().remove();

        var data = {
			'action': 'podo_change_dokument_status',
			 dataType: 'json',
			'id':id,
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(podo_object.ajax_url, data, function(response) {
				alert(response);
			location.reload();
		});


    }
    else{
        return false;
    }
});

// Month Filter
jQuery(".resetMonth .custom_filters").change(function(){
	var val = jQuery(this).val();

   var data = {
		'action': 'podo_filter_by_month',
		 dataType: 'json',
		'val':val,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {
		jQuery("#pending ul").html(response);	
	});

});


// Year Filter
jQuery(".resetyear .custom_filters").change(function(){
	var val = jQuery(this).val();

   var data = {
		'action': 'podo_filter_by_year',
		 dataType: 'json',
		'val':val,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {
		jQuery("#pending ul").html(response);	
	});

});


jQuery("#extruderLeft").buildMbExtruder({
    position:"right",
    width:400,
    extruderOpacity:.8,
    hidePanelsOnClose:true,
    accordionPanels:true,
    onExtOpen:function(){},
    onExtContentLoad:function(){},
    onExtClose:function(){}
});


jQuery("#extruderLeft2").buildMbExtruder({
    position:"right",
    width:400,
    extruderOpacity:.8,
    hidePanelsOnClose:true,
    accordionPanels:true,
    onExtOpen:function(){},
    onExtContentLoad:function(){},
    onExtClose:function(){}
});



//Edit a Treatment
jQuery('body').on('click', '.edit_treatment', function(e) {
	e.preventDefault();
	var id = jQuery(this).attr("data-id");

	var data = {
		'action': 'podo_edit_treatment',
		'id':id,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {
		jQuery("#extruderLeft2 .mainClassDokument").html(response);
	});

});

jQuery('body').on('click', '.customers_settings ul', function(e) {
	jQuery(this).find(".extended_bar").slideToggle();
});

// Order By Filters
jQuery("#order_filter").change(function(){
	var val = jQuery(this).val();

   var data = {
		'action': 'podo_order_by_filter',
		 dataType: 'json',
		'filter':val,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {
		jQuery(".customer_listed").html(response);	
	});

});


//jQuery("#search_filter").click(function(){ 
jQuery("#search_filter").keyup(function(){
    var keyword = jQuery("#search_filter").val();

    var data = {
		'action': 'podo_search_customer_filter',
		 dataType: 'json',
		'search':keyword,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {
		jQuery(".customer_listed").html(response);	
	});
});

// Order By Filters
jQuery("#order_by").change(function(){
	var val = jQuery(this).val();
   var data = {
		'action': 'podo_order_by_treatment_details',
		 dataType: 'json',
		'filter':val,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {
		jQuery(".treatments_settings").html(response);	
	});

});


//Anamnese Search
jQuery("#anam_search").keyup(function(){
    var keyword = jQuery(this).val();

    var data = {
		'action': 'podo_search_anamnese_filter',
		 dataType: 'json',
		'search':keyword,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(podo_object.ajax_url, data, function(response) {
		jQuery(".anamnese_settings").html(response);	
	});
});
jQuery('body').on('change', '#enableQR', function(e) {

	if(jQuery(this).is(':checked')){
		jQuery(".upBox").fadeIn("slow");
	}else{
		jQuery(".upBox").fadeOut("slow");
	}

});

});