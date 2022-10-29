Dropzone.autoDiscover = false; 

jQuery( document ).ready(function() {

if(jQuery('div#mydropzone').length) {
	// init DropzoneJS
	var myDropzone = new Dropzone("div#mydropzone", {
		

		url: vw_object.upload_file,
		acceptedFiles: 'image/*', // accepted file types
		maxFilesize: 10, // MB
		addRemoveLinks: true,
		maxFiles: 5,

		init: function () {
			//console.log(vw_object.file_loader_before);
	        let myDropzone = this;
	        jQuery.ajax({
	            type: 'post',
	            url: vw_object.file_loader_before,
	            data:{
	            	"post_id": jQuery("#post_ID").val(),
	            },
	            success: function(mocks){

	            	//console.log(mocks);

	                jQuery.each(JSON.parse(mocks), function(key,value) {
	                	//console.log(value.name);
	                    let mockFile = { name: value.name, size: value.size };
	                    myDropzone.displayExistingFile(mockFile, value.path);
	                });
	            },
	            error: function(xhr, durum, hata) {
	                console.log("ERROR");
	            }
	        });
	    },
		

		//success file upload handling
		success: function (file, response) {
			// handle your response object
			//console.log(response);

			jQuery.ajax({
				type: 'POST',
				url: vw_object.move_file,
				data: {
					"post_id": jQuery("#post_ID").val(),
					"status": "before",
				},
				// handle response from server
				success: function (response) {
					// handle your response object
					//console.log(response);
				},
			});

		},

		//error while handling file upload
		error: function (file,response) {
			file.previewElement.classList.add("dz-error");
		},

		// removing uploaded images
		removedfile: function(file) {
			var _ref;  

			// AJAX request for attachment removing
			jQuery.ajax({
				type: 'POST',
				url: vw_object.delete_file,
				data: {
					'image_name': file.name,
					"post_id": jQuery("#post_ID").val(),
					"status": "before",
				},
				// handle response from server
				success: function (response) {
					// handle your response object
					//console.log(response);
				},
			});
			
			return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;        
		}
	});

}

	// DropZone After
if(jQuery('div#mydropzone2').length) {
//if (document.getElementById('div#mydropzone2')) {
		// init DropzoneJS
	var myDropzone = new Dropzone("div#mydropzone2", {
		
		url: vw_object.upload_file,
		acceptedFiles: 'image/*', // accepted file types
		maxFilesize: 10, // MB
		addRemoveLinks: true,
		maxFiles: 5,

		init: function () {
			//console.log(vw_object.file_loader_before);
	        let myDropzone = this;
	        jQuery.ajax({
	            type: 'post',
	            url: vw_object.file_loader_after,
	            data:{
	            	"post_id": jQuery("#post_ID").val(),
	            },
	            success: function(mocks2){

	            	//console.log(mocks);

	                jQuery.each(JSON.parse(mocks2), function(key,value) {
	                	//console.log(value.name);
	                    let mockFile = { name: value.name, size: value.size };
	                    myDropzone.displayExistingFile(mockFile, value.path);
	                });
	            },
	            error: function(xhr, durum, hata) {
	                console.log("ERROR");
	            }
	        });
	    },
		

		//success file upload handling
		success: function (file, response) {
			// handle your response object
			console.log(response);

			jQuery.ajax({
				type: 'POST',
				url: vw_object.move_file,
				data: {
					"post_id": jQuery("#post_ID").val(),
					"status": "after",
				},
				// handle response from server
				success: function (response) {
					// handle your response object
					console.log(response);
				},
			});

		},

		//error while handling file upload
		error: function (file,response) {
			file.previewElement.classList.add("dz-error");
		},

		// removing uploaded images
		removedfile: function(file) {
			var _ref;  

			// AJAX request for attachment removing
			jQuery.ajax({
				type: 'POST',
				url: vw_object.delete_file,
				data: {
					'image_name': file.name,
					"post_id": jQuery("#post_ID").val(),
					"status": "after",
				},
				// handle response from server
				success: function (response) {
					// handle your response object
					console.log(response);
				},
			});
			
			return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;        
		}
	});
}


	jQuery(document).on("click",".send_invoice", function(e){

		e.preventDefault();

		jQuery(this).text("Generating");

		var data = {
			'action': 'vm_invoice_generator',
			'post_id': jQuery(this).attr("data-id")
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(vw_object.invoice_generator, data, function(response) {
			alert(response);
			location.reload();
		});

	});
	
});
