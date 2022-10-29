jQuery(document).ready(function(){

//Show Job Details
jQuery('body').on('click', '.ae-jobs', function(e) {
	e.preventDefault();
	var id = jQuery(this).attr("data-id");

	var data = {
		'action': 'ea_view_jobs',
		'id':id,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ae_object.ajax_url, data, function(response) {
		localStorage.setItem("ea_post_id", id);
		Swal.fire({
		  	html: response,  
		 	showCancelButton: false, allowOutsideClick: false,
		 	showConfirmButton: false, showCloseButton: true,
	 		width: '900px',
			showClass: {
                 popup: 'animated fadeIn',
                 icon: 'animated heartBeat delay-2s'
            },
            hideClass: {
                popup: 'animated fadeOutUp faster',
            }
		});
	});
});


//Show Apply Details
jQuery('body').on('click', '.directApply', function(e) {
	e.preventDefault();
	var id = localStorage.getItem("ea_post_id");
	var data = {
		'action': 'ea_apply_form',
		'id':id,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ae_object.ajax_url, data, function(response) {

		Swal.fire({
		  	html: response,  
		 	showCancelButton: false, allowOutsideClick: false,
		 	showConfirmButton: false, showCloseButton: true,
	 		width: '500px',
			showClass: {
                 popup: 'animated fadeIn',
                 icon: 'animated heartBeat delay-2s'
            },
            hideClass: {
                popup: 'animated fadeOutUp faster',
            }
		});
	});
});

// CV Uploader
jQuery('body').on('click', '#attach_cv_img', function(e) {
	jQuery('#attach_cv').trigger('click'); 
});

jQuery('body').on('submit', '#submit_application', function(e){
    e.preventDefault();
    var fd = new FormData(jQuery('#submit_application')[0]);
    var id = localStorage.getItem("ea_post_id");
    var files_data = jQuery('#attach_cv'); 
	var fileExtension = ['pdf','doc','docx','odt'];

    if(document.getElementById("attach_cv").files.length == 0){

	     if(!confirm("Attaching your CV is recommended- Are you sure you want to proceed without attaching your CV?")) {
			return false;
		} 
		
		 else if(jQuery.inArray(jQuery(files_data).val().split('.').pop().toLowerCase(),fileExtension)==-1 && document.getElementById("attach_cv").files.length != 0) 		 {
				alert("Only formats are allowed : "+fileExtension.join(', '));
				return false;
				  
		}
		
		else{


			


		    // Loop through each data and create an array file[] containing our files data.
		    if(files_data != ""){
			    jQuery.each(jQuery(files_data), function(i, obj) {
			        jQuery.each(obj.files,function(j,file){
			            fd.append('upimg[' + j + ']', file);
			        })
			    });
			}

		    fd.append('your_name', jQuery("#your_name").val());
		    fd.append('your_phone',jQuery("#your_phone").val());
		    fd.append('your_email', jQuery("#your_email").val());
		    fd.append('additional_comments', jQuery("#additional_comments").val());
		    fd.append('id', id);
		 
		    // our AJAX identifier
		    fd.append('action', 'ea_insert_application');  

		    jQuery.ajax({
		        type: 'POST',
		        url: ae_object.ajax_url,
		        data: fd,
		        dataType:'json',
		        contentType: false,
		        processData: false,
		        success: function(response){

			        if(response.status == 1){
						Swal.fire({
						  	title: "Success <hr>", 
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
						  	title: "Fail!<hr>", 
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
		}
    }else{


    	if (jQuery.inArray(jQuery(files_data).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
		    alert("Only formats are allowed : "+fileExtension.join(', '));
		    return false;
		}


    	   // Loop through each data and create an array file[] containing our files data.
		    if(files_data != ""){
			    jQuery.each(jQuery(files_data), function(i, obj) {
			        jQuery.each(obj.files,function(j,file){
			            fd.append('upimg[' + j + ']', file);
			        })
			    });
			}

		    fd.append('your_name', jQuery("#your_name").val());
		    fd.append('your_phone',jQuery("#your_phone").val());
		    fd.append('your_email', jQuery("#your_email").val());
		    fd.append('additional_comments', jQuery("#additional_comments").val());
		    fd.append('id', id);
		 
		    // our AJAX identifier
		    fd.append('action', 'ea_insert_application');  

		    jQuery.ajax({
		        type: 'POST',
		        url: ae_object.ajax_url,
		        data: fd,
		        dataType:'json',
		        contentType: false,
		        processData: false,
		        success: function(response){

			        if(response.status == 1){
						Swal.fire({
						  	title: "Success <hr>", 
						  	html: response.message,  
						 	showCancelButton: false, allowOutsideClick: false,
						 	showConfirmButton: true,
						 	icon:'success',
							showClass: {
                 				popup: 'animated fadeIn',
                 				icon: 'animated heartBeat delay-2s'
            				},
            				hideClass: {
                				popup: 'animated fadeOutUp faster',
            				}
					 	}).then(function(){ 
								location.reload();
						   }
						);
					}else{
						Swal.fire({
						  	title: "Fail!<hr>", 
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
    }
});

	
//Show Full List
jQuery('body').on('click', '.fullList', function(e) {
	e.preventDefault();
	var id = jQuery(this).attr("data-id");

	var data = {
		'action': 'ea_show_full_list',
		'list':1,
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ae_object.ajax_url, data, function(response) {
		//console.log(response);
		jQuery(".vjobs tbody tr:last").after(response);
		jQuery(".fullList").addClass("shortlist");
		jQuery(".fullList").text("Click here for short list");
		jQuery(".fullList").removeClass("fullList");
	});
});

//Hide Not Short List
jQuery('body').on('click', '.shortlist', function(e) {
	e.preventDefault();
	    jQuery(".shortlist").addClass("fullList");
		jQuery(".shortlist").text("Click here for full list");
		jQuery(".notshortlist").remove();
		jQuery(".shortlist").removeClass("shortlist");
});



});