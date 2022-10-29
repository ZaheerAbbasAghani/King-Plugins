jQuery(document).ready(function() {

jQuery(document).on('click', '#upload', function(e){
    e.preventDefault();

    jQuery(this).attr("disabled", true);
    jQuery(this).val("Creating Products...");

    var fd = new FormData();
    var file = jQuery(document).find('input[type="file"]');
    //var caption = jQuery(this).find('input[name=img_caption]');
    var individual_file = file[0].files[0];
    fd.append("file", individual_file);
    //var individual_capt = caption.val();
    //fd.append("caption", individual_capt);  
    fd.append('action', 'wcpu_csv_upload');  

    jQuery.ajax({
        type: 'POST',
        url: wcpu_ajax_object.ajax_url,
        data: fd,
        contentType: false,
        processData: false,
        success: function(response){
        	jQuery('.response').html(response);	
        }
    });
});


if(jQuery.cookie("cycle") == 1){

	jQuery("#upload").attr("disabled", true);
    jQuery("#upload").val("Creating Products...");

    var fd = new FormData();
    //var file = jQuery(document).find('input[type="file"]');
    //var caption = jQuery(this).find('input[name=img_caption]');
   // var individual_file = file[0].files[0];
   // fd.append("file", individual_file);
    //var individual_capt = caption.val();
    //fd.append("caption", individual_capt);  
    fd.append('action', 'wcpu_csv_upload');  

    jQuery.ajax({
        type: 'POST',
        url: wcpu_ajax_object.ajax_url,
        data: fd,
        contentType: false,
        processData: false,
        success: function(response){
        	//jQuery('.response').html(response.products+" Products Uploaded");	
        	console.log(response);
        }
    });

}

	//jQuery(document).on("submit", "#upload_csv_file", function(e){
/*	jQuery('#upload_csv_file').on("submit", function(e){  
		e.preventDefault();

		var formData = new FormData(jQuery(this)[0]);
		
		formData.append("csv_file", jQuery("#csv_file").val());
		formData.append("action", 'wcpu_csv_upload');

		var data = {
			//'action': 'wcpu_csv_upload',
			//url: 
			method:"POST",  
			data:formData,  
		
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(wcpu_ajax_object.ajax_url, data, function(response) {
			console.log(response);
		});
	});*/
});