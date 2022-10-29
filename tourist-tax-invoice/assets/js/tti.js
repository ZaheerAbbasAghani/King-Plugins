jQuery(document).ready(function(){
	jQuery.validator.addMethod('date', function(value, element, param) {
		return (value != 0) && (value <= 31) && (value == parseInt(value, 10));
	}, 'Please enter a valid date!');
	jQuery.validator.addMethod('month', function(value, element, param) {
		return (value != 0) && (value <= 12) && (value == parseInt(value, 10));
	}, 'Please enter a valid month!');
	jQuery.validator.addMethod('year', function(value, element, param) {
		return (value != 0) && (value >= 1900) && (value == parseInt(value, 10));
	}, 'Please enter a valid year not less than 1900!');
	jQuery.validator.addMethod('username', function(value, element, param) {
		var nameRegex = /^[a-zA-Z0-9]+$/;
		return value.match(nameRegex);
	}, 'Only a-z, A-Z, 0-9 characters are allowed');

	var val	=	{
	    // Specify validation rules
	    rules: {
	      fname: "required",
	      email: {
			        required: true,
			        email: true
			      },
			phone: {
				required:true,
				minlength:10,
				maxlength:10,
				digits:true
			},
			date:{
				date:true,
				required:true,
				minlength:2,
				maxlength:2,
				digits:true
			},
			month:{
				month:true,
				required:true,
				minlength:2,
				maxlength:2,
				digits:true
			},
			year:{
				year:true,
				required:true,
				minlength:4,
				maxlength:4,
				digits:true
			}
	    },
	    // Specify validation error messages
	    messages: {
			fname: "Your name is required",
			email: {
				required: 	"Email is required",
				email: 		"Please enter a valid e-mail",
			},
			phone:{
				required: 	"Phone number is requied",
				minlength: 	"Please enter 11 digit mobile number",
				maxlength: 	"Please enter 11 digit mobile number",
				digits: 	"Only numbers are allowed in this field"
			},
			date:{
				required: 	"Date is required",
				minlength: 	"Date should be a 2 digit number, e.i., 01 or 20",
				maxlength: 	"Date should be a 2 digit number, e.i., 01 or 20",
				digits: 	"Date should be a number"
			},
			month:{
				required: 	"Month is required",
				minlength: 	"Month should be a 2 digit number, e.i., 01 or 12",
				maxlength: 	"Month should be a 2 digit number, e.i., 01 or 12",
				digits: 	"Only numbers are allowed in this field"
			},
			year:{
				required: 	"Year is required",
				minlength: 	"Year should be a 4 digit number, e.i., 2018 or 1990",
				maxlength: 	"Year should be a 4 digit number, e.i., 2018 or 1990",
				digits: 	"Only numbers are allowed in this field"
			}
	    }
	}
	jQuery("#myForm").multiStepForm(
	{
		defaultStep:0,
		beforeSubmit : function(form, submit){
			console.log("called before submiting the form");
			console.log(form);
			console.log(submit);
		},
		validations:val,
	}
	).navigateTo(0);


	// Upload Logo


	var custom_uploader
      , click_elem = jQuery('.tti-upload-logo')
     
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
            //target.val(attachment.url);
            console.log(attachment.url);
            jQuery("#uploaded_logo").val(attachment.url);
            jQuery(".tti_settings #logo").css("background-image","url('"+attachment.url+"')");
        });
        //Open the uploader dialog
        custom_uploader.open();
    });      



});


jQuery(document).ready(function() {
    
    jQuery('#example').DataTable( {
        "order": [[ 4, "desc" ]]
    } );

    jQuery(".delete_row").click(function(e){
    	e.preventDefault();

	    if(confirm("Wirklich entfernen?")){
	        
	        var id = jQuery(this).attr("data-id");

	         var data = {
	          'action': 'tti_delete_tourist_info',
	          'id': id
	        };

	        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	        jQuery.post(tti_ajax_object.ajax_url, data, function(response) {
	            alert(response);
	            location.reload();
	        });

	    }
	    else{
	        return false;
	    }
    });


    jQuery(".invoice").click(function(e){
    	e.preventDefault();

	
	        
	        var id = jQuery(this).attr("data-id");

	         var data = {
	          'action': 'tti_generate_invoice',
	          'id': id
	        };

	        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	        jQuery.post(tti_ajax_object.ajax_url, data, function(response) {
	        	//console.log(response);
			    Swal.fire({
				  	title: "Rechnung-Vorschau: <hr>", 
				  	html: response,  
				 	showCancelButton: false, allowOutsideClick: true,
				 	showConfirmButton: false,
				 	showCloseButton: true,
				 	width: '850px'	
				});

	        });

    });


    //jQuery(".delete_row").click(function(e){
   /* jQuery(document).on("click", ".downloadPdf", function(e){

    	e.preventDefault();


	    var id = jQuery(this).attr("data-id");

         var data = {
          'action': 'tti_download_invoice',
          'id': id
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(tti_ajax_object.ajax_url, data, function(response) {
        	console.log(response);
        	//location.reload();
		});



    });*/
    //downloadPdf

});