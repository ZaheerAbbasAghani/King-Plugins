jQuery(document).ready(function(){
var selected_customer = localStorage.getItem('selected_customer');	

if(selected_customer === null){
    console.log("Item does not exist in localstoarge");
}else{
   //console.log("Item exists in localstorage");
   jQuery(".customer_list li").each(function(){
   		var id = jQuery(this).attr("data-id");

   		if(id == selected_customer){
   			jQuery(this).addClass("grayclr");

		   	var data = {
				'action': 'anam_preview_customer',
				'id':id
			};

			jQuery.post(anam_object.ajax_url, data, function(response) {
				jQuery(".rightSide").html(response).fadeIn("slow");
				jQuery(".anam_wrapper .rightSide").css("opacity","1");
				jQuery("#suggesstion-box").hide();

			});

   			
   		}

   });
}


//Create new customer
jQuery('body').on('click', '.new_customer', function() {
	Swal.fire({
	  	title: "Neuen Patienten erstellen<hr>", 
	  	html: '<span class="section_title">Patienten Informationen</span><div class="cform"><form method="post" action id="create_new_customer"><div class="column"><label>Vorname: <input name="first_name" id="first_name" placeholder="Geben Sie Ihren Vornamen ein" required></label><label>Nachname:<input name="last_name" id="last_name" placeholder="Geben Sie Ihren Nachnamen ein" required></label><label>E-Mail: <input type="email" name="email_address" id="email_address" placeholder="Geben Sie Ihre E-Mail-Adresse ein address"></label></div><div class="column"><label>Geschlecht:<select id="gender" name="gender" required><option value disabled selected>Geschlecht</option><option value="Herr">Herr</option><option value="Frau">Frau</option></select></label><label>Geburtstag:<input type="date" name="birth_date" id="birth_date" placeholder="Birth Date" required></label><label>Handynummer: <input type="tel" name="mobile_no" id="mobile_no" placeholder="Geben Sie Ihre Handynummer ein" required></label></div><div class="column"><label>Beruf: <input name="job" id="job" placeholder="Geben Sie Ihren Beruf ein"></label><label>Wohnort:<input name="city" id="city" placeholder="Geben Sie Ihren Wohnort ein" required></label><label>Postleitzahl: <input name="zipcode" id="zipcode" placeholder="Geben Sie Ihre Postleitzahl ein" required></label></div><div class="column_full"><label>Adresse:<textarea placeholder="Geben Sie Ihre Adresse ein" name="address" id="address" row="5" cols="5"></textarea></label><label>Datum der ersten Behandlung: <input type="date" name="fad" id="fad" placeholder="Datum der ersten Behandlung"></label></div><span class="section_title2">Medizinische Informationen</span><div class="column"><label>Arzt: <input type="text" name="doctor_name" id="doctor_name" placeholder="Geben Sie den Namen Ihres Arztes ein"></label><label>Diagnose:<input name="diagnosis" id="diagnosis" placeholder="Geben Sie Ihre Diagnose ein"></label><label>Telefonnummer des Arztes: <input type="tel" name="phone_of_doctor" id="phone_of_doctor" placeholder="Geben Sie die Telefonnummer des Arztes ein"></label></div><div class="column"><label>Medikamente <input name="drugs" id="drugs" placeholder="Nehmen Sie Medikamente?"></label><label>Versicherung: <input name="insurance_company" id="insurance_company" placeholder="Bei welcher Versicherung sind Sie?"></label><label>Vorerkrankungen: <input name="vorerkrankungen" id="vorerkrankungen" placeholder="Vorerkrankungen"></label></div><div class="column_full"><label>Wichtige Informationen:<textarea placeholder="Müssen wir etwas Wichtiges wissen?" name="important_notes" id="important_notes" row="5" cols="5" ></textarea></label><label><input type="checkbox" name="agree_on_terms" required>Ich bin einverstanden, dass meine persönlichen Daten für Behandlungszwecke gespeichert werden </label></div><input type="submit" class="button" value="Senden"></form></div>',  
	 	showCancelButton: false, allowOutsideClick: false,
	 	showConfirmButton: false, showCloseButton: true,
 		width: '1200px'	
	});


});

// Senden customer
jQuery(document).on('submit','#create_new_customer',function(e){

	e.preventDefault();
	jQuery("#create_new_customer .button").val("Loading...");
   	var data = {
		'action': 'anam_create_new_customer',
		 dataType: 'json',
		'customer_info':jQuery(this).serialize()
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(anam_object.ajax_url, data, function(response) {
		
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

// Senden customer
jQuery(document).on('submit','#create_new_customer2',function(e){

	e.preventDefault();
	jQuery("#create_new_customer2 .button").val("Loading...");
   	var data = {
		'action': 'anam_create_new_customer_2',
		 dataType: 'json',
		'customer_info':jQuery(this).serialize()
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(anam_object.ajax_url, data, function(response) {
		
		if(response.status == 1){
			Swal.fire({
			  	title: "Vielen Dank!<hr>", 
			  	html: response.message,  
			 	showCancelButton: false, allowOutsideClick: false,
			 	showConfirmButton: true,
			 	icon:'success',
		 	}).then(function(){ 
					//location.reload();
					document.location.href="/";
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

jQuery(document).on('click','.delete_customer a',function(e){
//jQuery(".delete_customer a").click(function(e){
	e.preventDefault();
	if( !confirm('Sind Sie sich sicher, dass Sie den Patienten permanent löschen möchten?')) {
		return false;
	}else{
		
		var id = jQuery(this).attr("data-id");
		var cid = jQuery(".customer_edit_btn a").attr("data-id");
		localStorage.setItem("selected_customer",cid);

		var data = {
			'action': 'anam_remove_customer',
			'id':id
		};
	}
	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(anam_object.ajax_url, data, function(response) {
		alert(response);
		 location.reload();
	});

});
jQuery('body').on('click', 'ul.customer_list li a', function(e) {
	e.preventDefault();
	jQuery(".anam_wrapper .rightSide").css("opacity","0.4");
	var id = jQuery(this).parent().attr("data-id");
	jQuery(".delete_customer a").attr("data-id",id);
	jQuery(this).parent().addClass("grayclr").siblings().removeClass('grayclr');
	localStorage.removeItem("selected_customer");
	var data = {
		'action': 'anam_preview_customer',
		'id':id
	};

	jQuery.post(anam_object.ajax_url, data, function(response) {
		jQuery(".rightSide").html(response).fadeIn("slow");
		jQuery(".anam_wrapper .rightSide").css("opacity","1");
		jQuery("#suggesstion-box").hide();

	});
});


//Edit a customer
jQuery('body').on('click', '.edit_customer', function(e) {
	e.preventDefault();
	var id = jQuery(this).attr("data-id");

	var data = {
		'action': 'anam_edit_customer',
		'id':id,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(anam_object.ajax_url, data, function(response) {

		Swal.fire({
		  	title: "Patienten bearbeiten <hr>", 
		  	html: response,  
		 	showCancelButton: false, allowOutsideClick: false,
		 	showConfirmButton: false, showCloseButton: true,
	 		width: '1200px'	
		});

	});

});


	// Senden customer
	jQuery(document).on('submit','#update_new_customer',function(e){

		e.preventDefault();
		jQuery("#update_new_customer .button").val("Loading...");
		var id = jQuery("#cid").val();
		localStorage.setItem('selected_customer', id);
       	
       	var data = {
			'action': 'anam_update_customer',
			 dataType: 'json',
			'customer_info':jQuery(this).serialize()
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(anam_object.ajax_url, data, function(response) {

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


jQuery("#search_customer").keyup(function(){

		var data = {
			'action': 'anam_search_customer',
			'keyword':jQuery(this).val(),
		};
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(anam_object.ajax_url, data, function(response) {
			jQuery("#suggesstion-box").show();
			jQuery("#suggesstion-box").html(response);
		});
});




// Senden customer
jQuery(document).on('click','.create_doc',function(e){

	e.preventDefault();
	var id = jQuery(this).attr("data-id");
   	var data = {
		'action': 'anam_create_new_document',
		'id':id,
		dataType: 'json',
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php

	jQuery.post(anam_object.ajax_url, data, function(response) {
		//console.log(response);
		Swal.fire({
		  	title: "Neue Anamnese <hr>", 
		  	html: response,  
		 	showCancelButton: false, allowOutsideClick: false,
		 	showConfirmButton: false, showCloseButton: true,
	 		width: '1200px'	
		});

	});
});


/*
Insert Anamnese.
*/

jQuery('body').on('submit', '#docform', function(e){
    e.preventDefault();
    jQuery("#docform .button").val("Loading...");
    jQuery("#docform .button").attr("disabled", true);
    var id = jQuery("input[name='user_id']").val();
    localStorage.setItem('selected_customer', id);

	var data = {
		'action': 'anam_insert_new_document',
		dataType: 'json',
		'doc_info':jQuery(this).serialize()
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(anam_object.ajax_url, data, function(response) {

		//console.log(response);

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


   // var fd = new FormData(jQuery('#docform')[0]);
   /* var files_data = jQuery('.files-data'); // The <input type="file" /> field
   
    // Loop through each data and create an array file[] containing our files data.
    jQuery.each(jQuery(files_data), function(i, obj) {
    //	console.log(obj.files);
        jQuery.each(obj.files,function(j,file){
            fd.append('upimg[' + j + ']', file);
            //console.log(file);
        })
    });*/
   	
    // our AJAX identifier
  /*  fd.append('action', 'anam_insert_new_document');  

    jQuery.ajax({
        type: 'POST',
        url: anam_object.ajax_url,
        data: fd,
        dataType:'json',
        contentType: false,
        processData: false,
        success: function(response){

        	print_r(response);

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
        }
    });*/
});

	
// Edit Anamnese
jQuery('body').on('click', '.edit_document', function(e) {
	e.preventDefault();
	var id = jQuery(this).attr("data-id");

	var data = {
		'action': 'anam_edit_document',
		'id':id,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(anam_object.ajax_url, data, function(response) {
		Swal.fire({
		  	title: "Editieren Anamnese <hr>", 
		  	html: response,  
		 	showCancelButton: false, allowOutsideClick: false,
		 	showConfirmButton: false, showCloseButton: true,
	 		width: '1200px'	
		});
	});

});


// Update document
jQuery(document).on('submit','#docUpdateform',function(e){

e.preventDefault();
jQuery("#docUpdateform .button").val("Loading...");
	var data = {
	'action': 'anam_update_document',
	// dataType: 'json',
	'customer_info':jQuery(this).find("input[type='text'],input[type='checkbox'], input[type='hidden'], input[type='number'],input[type='date'], textarea, select").serialize()
};

// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(anam_object.ajax_url, data, function(response) {
		//console.log(response);
		if(response.status == 1){
			Swal.fire({
			  	title: "Erfolgreich<hr>", 
			  	html: response.message,  
			 	showCancelButton: false, allowOutsideClick: false,
			 	showConfirmButton: true,showCloseButton: true,
			 	icon:'success',
		 	});
		}else{
			Swal.fire({
			  	title: "Fehler!<hr>", 
			  	html: response.message,  
			 	showCancelButton: false, allowOutsideClick: false,
			 	showConfirmButton: true,
			 	icon: 'error',
		 	});
		}

	});
});


// Anamnese Details
jQuery(document).on('click','.doc_title',function(e){

e.preventDefault();
	var data = {
		'action': 'anam_preview_document',
		'id':jQuery(this).attr("data-id"),
		'user_id':jQuery(this).attr("user-id"),
		
	};


// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(anam_object.ajax_url, data, function(response) {


			Swal.fire({
			  	title: "Patienten Anamnese <hr>", 
			  	html: response,  
			 	showCancelButton: false, allowOutsideClick: false,
			 	showConfirmButton: true,showCloseButton: true,
			 	width:1200,
		 	});


		/*
			Sorting Li
		*/
		jQuery(".patienten_anamnese li").each(function(){
			var sort = jQuery(this).attr("data-sort");
			if(sort == ""){
				var html = jQuery(this).html();
				jQuery(this).remove();
				jQuery(".patienten_anamnese li").last().after("<li>"+html+"</li>");

			}

			if(sort != ""){
				jQuery(this).css({"background":"#1cc0e3","color":"#fff"});
			}

		});


		var ul = jQuery(".patienten_anamnese li").length;
		console.log(ul / 3);


	});





});

jQuery(document).on('click', '.parent-container', function (e) {
	e.preventDefault();
 	   jQuery(this).magnificPopup({
 	   	 		delegate: 'a',
                type: 'image',
               // mainClass: 'mfp-with-zoom mfp-img-mobile',
                gallery: {
           		 	enabled: true,
            		navigateByImgClick: true,

        		}
            }).magnificPopup('open');
});

// Show hide anamesen
jQuery(document).on('click', '.view_anamnesen', function (e) {
	e.preventDefault();
	jQuery(".anam_bx").toggle();
});

jQuery(document).on('keyup', "#search_treatments",function () {

	jQuery.ajax({
	type: "POST",
	url: anam_object.ajax_url,
	data: {
        "action": 'anam_search_for_treatment',
        "keyword": jQuery(this).val()
	},
	beforeSend: function(){
		//jQuery(".search_treatments").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
	},
	success: function(data){
		jQuery(".autocomplete-box").show();
		jQuery(".autocomplete-box").html(data);
	}
	});
});




// Create Dokument
jQuery(document).on('click','.create_dokument',function(e){
	e.preventDefault();
	var id = jQuery(this).attr("data-id");
	localStorage.setItem('selected_customer', id);
   	var data = {
		'action': 'anam_create_new_dokument',
		'id':id
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php

	jQuery.post(anam_object.ajax_url, data, function(response) {
		Swal.fire({
		  	title: "Neue Dokumentation <hr>", 
		  	html: response,  
		 	showCancelButton: false, allowOutsideClick: false,
		 	showConfirmButton: false, showCloseButton: true,
	 		width: '1200px'	
		});
	});
});


jQuery('body').on('submit', '#dokument_form', function(e){
    e.preventDefault();
    jQuery("#dokument_form .button").val("Loading...");
    jQuery("#dokument_form .button").attr("disabled", true);
    var id = jQuery("#user_id").val();
	localStorage.setItem('selected_customer', id);    
    var fd = new FormData(jQuery('#dokument_form')[0]);
    var files_data = jQuery('.files-data'); 
    // Loop through each data and create an array file[] containing our files data.
    if(files_data != ""){
	    jQuery.each(jQuery(files_data), function(i, obj) {
	        jQuery.each(obj.files,function(j,file){
	            fd.append('upimg[' + j + ']', file);
	        })
	    });
	}
    fd.append('search_treatments',jQuery("#search_treatments").val());

    fd.append('tprice', jQuery("#tprice").val());
    fd.append('addition_information',jQuery("#addition_information").val());
    fd.append('email_pdf', jQuery(".email_pdf").val());
    fd.append('payment_methods', jQuery("#payment_methods").val());
    fd.append('payment_status', jQuery("#payment_status").val());
    fd.append('duration', jQuery("#duration").val());
    fd.append('user_id', jQuery("#user_id").val());
    fd.append('doctor_id', jQuery("#doctor_id").val());
   	
    // our AJAX identifier
    fd.append('action', 'anam_insert_new_dokument');  

    jQuery.ajax({
        type: 'POST',
        url: anam_object.ajax_url,
        data: fd,
        dataType:'json',
        contentType: false,
        processData: false,
        success: function(response){

//		  	console.log(response);

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
        }
    });

});


// Anamnese Details
jQuery(document).on('click','.dok_title',function(e){
	e.preventDefault();

	var data = {
		'action': 'anam_preview_dokument',
		'id':jQuery(this).attr("data-id"),
		'user_id':jQuery(this).attr("user-id"),
		// dataType: 'json',
		
	};

// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(anam_object.ajax_url, data, function(response) {
			Swal.fire({
			  	title: "Patienten Dokumentation <hr>", 
			  	html: response,  
			 	showCancelButton: false, allowOutsideClick: false,
		 		showConfirmButton: false, showCloseButton: true,
			 	width:1200,
		 	});

	});
});


// Edit Anamnese
jQuery('body').on('click', '.edit_dokument', function(e) {
	e.preventDefault();
	var id = jQuery(this).attr("data-id");

	var data = {
		'action': 'anam_edit_dokument',
		'id':id,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(anam_object.ajax_url, data, function(response) {
		Swal.fire({
		  	title: "Editieren Anamnese <hr>", 
		  	html: response,  
		 	showCancelButton: false, allowOutsideClick: false,
		 	showConfirmButton: false, showCloseButton: true,
	 		width: '1200px'	
		});
	});
});



// update dokument
jQuery(document).on('submit','#dokument_update_form',function(e){

	e.preventDefault();
	jQuery("#dokument_update_form .button").val("Loading...");
   	var data = {
		'action': 'anam_update_dokument',
		'dok_info':jQuery(this).serialize()
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(anam_object.ajax_url, data, function(response) {
	//	console.log(response);
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
		 	});
		}

	});
});

jQuery(document).click(function (e)
{
    var container = jQuery(".frmSearch");

    if (!container.is(e.target))
    {
        jQuery("#suggesstion-box").hide();
    }
});

// Remove Dokument

jQuery(document).on('click','.delete_dokument',function(e){
	e.preventDefault();
	if( !confirm('Sind Sie sich sicher, dass Sie diese Dokumentation permanent löschen möchten?')) {
		return false;
	}else{
		//localStorage.removeItem("selected_customer");
		var id = jQuery(this).attr("data-id");
		var cid = jQuery(".customer_edit_btn a").attr("data-id");
		localStorage.setItem("selected_customer",cid);
		
		var data = {
			'action': 'anam_remove_dokument',
			'id':id,
			 dataType: 'json',
		};
	}
	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(anam_object.ajax_url, data, function(response) {
		 if(response.status == 1){
		 	alert(response.message);
		 	 location.reload();
		}else{
			alert(response.message);
			 location.reload();
		}


	});
});


/*
	Fetch Treatment Price
*/
jQuery(document).on('click','.treatment-item',function(e){
//jQuery(".treatment-item").click(function(){
	var id = jQuery(this).attr("data-id");
	var data = {
		'action': 'anam_filter_treatment_prices',
		dataType: 'json',
		id:id
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(anam_object.ajax_url, data, function(response) {
		jQuery("#tprice").val(response.price);	
		jQuery("#duration").val(response.duration);	
	});
});


/*
	Fetch QR Code
*/
jQuery(document).on('change', '#payment_methods', function(e){
	var id = jQuery(this).val();
	var data = {
		'action': 'anam_filter_qr_code',
		dataType: 'json',
		"method":id
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(anam_object.ajax_url, data, function(response) {
		jQuery(".QRcode").html(response);
	});
});




});