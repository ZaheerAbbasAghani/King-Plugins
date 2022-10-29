jQuery(document).ready(function(){

	var data = {
		'action': 'gf_check_fields_availability',
		'fields': 1
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(gf_ajax_object.ajax_url, data, function(response) {

		jQuery(".loadingImg").remove();
		
		  var jQueryfbTemplate1 = jQuery(document.getElementById("build-wrap"));
		  var formData = JSON.stringify(response);


		  var formBuilder = jQueryfbTemplate1.formBuilder(
		  	addDependsOn({
			  	formData,
			  	disableFields: ['autocomplete', 'file','date', 'button', 'header'],
			  	disabledAttrs: ['access', 'description'],
			  	disabledActionButtons: ['data', 'clear'],
			  	sortableControls: true,
			  	scrollToFieldOnAdd: true,

				onSave: function(evt, formData) {
		
					var data = {
						'action': 'gf_store_form_data',
						'formData': formData
					};

					// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
					jQuery.post(gf_ajax_object.ajax_url, data, function(response1) {
						console.log(response1);
					});

		        },

		       /* onAddField: function(fieldId) {
    				console.log(fieldId);
  				},*/

		    }),
		  );
	});


	// Delete Row

	jQuery(document).on("click", "a.del-button", function(){
		var name = jQuery(this).parent().parent().find(".fld-name").val();

		var data = {
			'action': 'gf_remove_field_db',
			'name': name
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(gf_ajax_object.ajax_url, data, function(response) {
			console.log(response);
		});

	});



	jQuery(function () {
		jQuery('#build-wrap').sortable({
	  		items: ".form-field",
			sort: function( event, ui ) {
				alert("Sort Event Triggered!");
			}
		});
	});


});