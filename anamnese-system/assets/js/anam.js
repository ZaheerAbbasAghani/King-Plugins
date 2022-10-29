jQuery(document).ready(function(){
//Create new customer
jQuery('body').on('click', '.new_customer', function() {
	Swal.fire({
	  	title: "Create new Customer <hr>", 
	  	html: '<span class="section_title">Customer Information</span><div class="cform"><form method="post" action id="create_new_customer"><div class="column"><label>First Name: <input name="first_name" id="first_name" placeholder="Enter your first name" required></label><label>Last Name:<input name="last_name" id="last_name" placeholder="Enter your last name" required></label><label>Email: <input type="email" name="email_address" id="email_address" placeholder="Enter your email address"></label></div><div class="column"><label>Gender:<select id="gender" name="gender" required><option value disabled selected>Gender</option><option value="Male">Male</option><option value="Female">Female</option></select></label><label>Birthday:<input type="date" name="birth_date" id="birth_date" placeholder="Birth Date" required></label><label>Mobile Phone: <input type="tel" name="mobile_no" id="mobile_no" placeholder="Enter your mobile no" required></label></div><div class="column"><label>Job: <input name="job" id="job" placeholder="Enter your job"></label><label>City:<input name="city" id="city" placeholder="Enter your city name" required></label><label>Zip Code: <input name="zipcode" id="zipcode" placeholder="Enter your zip code" required></label></div><div class="column_full"><label>Address:<textarea placeholder="Enter your address" name="address" id="address" row="5" cols="5"></textarea></label><label>First Appointment Date: <input type="date" name="fad" id="fad" placeholder="Enter your first appointment date"></label></div><span class="section_title2">Medical Information</span><div class="column"><label>Doctor: <input type="text" name="doctor_name" id="doctor_name"></label><label>Diagnosis:<input name="diagnosis" id="diagnosis" placeholder="Enter your diagnosis details"></label><label>Phone of Doctor : <input type="tel" name="phone_of_doctor" id="phone_of_doctor" placeholder="Enter doctor phone number"></label></div><div class="column"><label>Drugs: <input name="drugs" id="drugs" placeholder="Enter drugs details"></label><label>Insurance Company: <input name="insurance_company" id="insurance_company" placeholder="Enter your insurance company details"></label></div><div class="column_full"><label>Important Notes:<textarea placeholder="Enter important notes" name="important_notes" id="important_notes" row="5" cols="5"></textarea></label></div><input type="submit" class="button" value="Submit"></form></div>',  
	 	showCancelButton: false,
	 	showConfirmButton: false,
 		width: '1200px'	
	});


});

	// Submit customer
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
				  	title: "Success<hr>", 
				  	html: response.message,  
				 	showCancelButton: false,
				 	showConfirmButton: true,
				 	icon:'success',
			 	}).then(function(){ 
   					location.reload();
				   }
				);
			}else{
				Swal.fire({
				  	title: "Failure!<hr>", 
				  	html: response.message,  
				 	showCancelButton: false,
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
	if( !confirm('Are you surea you wnt to delete customer?')) {
		return false;
	}else{
		var id = jQuery(this).attr("data-id");
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
	jQuery(this).parent().addClass("grayclr").siblings().removeClass('grayclr');
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
		  	title: "Edit Customer <hr>", 
		  	html: response,  
		 	showCancelButton: false,
		 	showConfirmButton: false,
	 		width: '1200px'	
		});

	});

});


	// Submit customer
	jQuery(document).on('submit','#update_new_customer',function(e){

		e.preventDefault();
		jQuery("#update_new_customer .button").val("Loading...");
       	var data = {
			'action': 'anam_update_customer',
			 dataType: 'json',
			'customer_info':jQuery(this).serialize()
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(anam_object.ajax_url, data, function(response) {

			if(response.status == 1){
				Swal.fire({
				  	title: "Success<hr>", 
				  	html: response.message,  
				 	showCancelButton: false,
				 	showConfirmButton: true,
				 	icon:'success',
			 	}).then(function(){ 
   					location.reload();
				   }
				);
			}else{
				Swal.fire({
				  	title: "Failure!<hr>", 
				  	html: response.message,  
				 	showCancelButton: false,
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


// Submit customer
jQuery(document).on('click','.create_doc',function(e){

	e.preventDefault();
	var id = jQuery(this).attr("data-id");
   	var data = {
		'action': 'anam_create_new_document',
		'id':id
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php

	jQuery.post(anam_object.ajax_url, data, function(response) {
		//console.log(response);

		Swal.fire({
		  	title: "Create new Document <hr>", 
		  	html: response,  
		 	showCancelButton: false,
		 	showConfirmButton: false,
	 		width: '1200px'	
		});

	});
});


/*
Insert Document.
*/

jQuery('body').on('submit', '#docform', function(e){
    e.preventDefault();
    jQuery("#docform .button").val("Loading...");
    jQuery("#docform .button").attr("disabled", true);
    var fd = new FormData(jQuery('#docform')[0]);
    var files_data = jQuery('.files-data'); // The <input type="file" /> field
   
    // Loop through each data and create an array file[] containing our files data.
    jQuery.each(jQuery(files_data), function(i, obj) {
    //	console.log(obj.files);
        jQuery.each(obj.files,function(j,file){
            fd.append('upimg[' + j + ']', file);
            //console.log(file);
        })
    });
   	
    // our AJAX identifier
    fd.append('action', 'anam_insert_new_document');  

    jQuery.ajax({
        type: 'POST',
        url: anam_object.ajax_url,
        data: fd,
        dataType:'json',
        contentType: false,
        processData: false,
        success: function(response){

        if(response.status == 1){
			Swal.fire({
			  	title: "Success<hr>", 
			  	html: response.message,  
			 	showCancelButton: false,
			 	showConfirmButton: true,
			 	icon:'success',
		 	}).then(function(){ 
					location.reload();
			   }
			);
		}else{
			Swal.fire({
			  	title: "Failure!<hr>", 
			  	html: response.message,  
			 	showCancelButton: false,
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

	
// Edit Document
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
		  	title: "Edit Document <hr>", 
		  	html: response,  
		 	showCancelButton: false,
		 	showConfirmButton: false,
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
	'customer_info':jQuery(this).serialize()
};

// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(anam_object.ajax_url, data, function(response) {
		//console.log(response);
		if(response.status == 1){
			Swal.fire({
			  	title: "Success<hr>", 
			  	html: response.message,  
			 	showCancelButton: false,
			 	showConfirmButton: true,
			 	icon:'success',
		 	}).then(function(){ 
					location.reload();
			   }
			);
		}else{
			Swal.fire({
			  	title: "Failure!<hr>", 
			  	html: response.message,  
			 	showCancelButton: false,
			 	showConfirmButton: true,
			 	icon: 'error',
		 	}).then(function(){ 
					location.reload();
			   }
			);
		}

	});
});


// Document Details
jQuery(document).on('click','.doc_title',function(e){

e.preventDefault();
	var data = {
		'action': 'anam_preview_document',
		'id':jQuery(this).attr("data-id"),
		'user_id':jQuery(this).attr("user-id"),
		// dataType: 'json',
		
	};

// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(anam_object.ajax_url, data, function(response) {
			Swal.fire({
			  	title: "User Document <hr>", 
			  	html: response,  
			 	showCancelButton: false,
			 	showConfirmButton: true,
			 	width:1200,
		 	}).then(function(){ 
					location.reload();
			   }
			);

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





});