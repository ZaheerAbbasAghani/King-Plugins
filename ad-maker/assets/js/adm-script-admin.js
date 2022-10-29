jQuery(document).ready(function(){

	var data = {
		'action': 'adm_check_fields_availability',
		'fields': 1
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php

	jQuery.post(adm_ajax_object.ajax_url, data, function(response) {

		jQuery(".loadingImg").remove();

		  	var jQueryfbTemplate1 = jQuery(document.getElementById("build-wrap"));
		  	var formData = JSON.stringify(response);

			var fields = [{
			  label: "Email",
			  type: "text",
			  subtype: "email",
			  icon: "✉"
			}];

		  var formBuilder = jQueryfbTemplate1.formBuilder({

			  	formData,
			  	fields,
			  	disableFields: ['button', 'header', 'hidden','paragraph','autocomplete','file'],
			  	disabledAttrs: ['access', 'description','min','max','step','other','maxlength','value'],
			  	disabledActionButtons: ['data', 'clear'],
			  	sortableControls: true,
			  	scrollToFieldOnAdd: true,
				onSave: function(evt, formData) {
					var data = {
						'action': 'adm_store_form_data',
						'formData': formData,
						'oldValue': localStorage.getItem('oldValue'),
						'newValue': localStorage.getItem('newValue'),

					};
					// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php

					jQuery.post(adm_ajax_object.ajax_url, data, function(response1) {
						//console.log(response1);	
						localStorage.removeItem("oldValue");
						localStorage.removeItem("newValue");
						toastr.success("Data Updated Successfully.");
					});
		        },	
		  });
	});

	// Delete Row
	jQuery(document).on("click", "a.del-button", function(){
		var name = jQuery(this).parent().parent().find(".fld-name").val();
		var label = jQuery(this).parent().parent().find(".field-label").text();
		var data = {
			'action': 'adm_remove_field_db',
			'name': name,
			'label':label,
		};
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(adm_ajax_object.ajax_url, data, function(response) {
			console.log(response);
		});
	});

	// Tabs
	jQuery('#tabs').tabs();

	jQuery(document).on("focus",".fld-label",function(){
		localStorage.setItem('oldValue', jQuery(this).text());
	});

	jQuery(document).on("blur",".fld-label",function(){
		localStorage.setItem('newValue', jQuery(this).text());
	});

	// DataTable
	jQuery('#example').DataTable();


	
	// Delete Row
	jQuery(document).on("click", "a.deleteItem", function(e){
		e.preventDefault();
		if (confirm('Are you sure?')) {
			jQuery(this).parent().parent().css("background","red");
			var id = jQuery(this).attr("data-id");
			//var label = jQuery(this).parent().parent().find(".field-label").text();
			var data = {
				'action': 'adm_remove_this_ad',
				'id': id,
				//'label':label,
			};
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(adm_ajax_object.ajax_url, data, function(response) {
				toastr.success("Data Updated Successfully.");
				location.reload();
			});
		}else{
			return false;
		}

	});


	 jQuery(function() {
    //  jQueryUI 1.10 and HTML5 ready
    //      http://jqueryui.com/upgrade-guide/1.10/#removed-cookie-option 
    //  Documentation
    //      http://api.jqueryui.com/tabs/#option-active
    //      http://api.jqueryui.com/tabs/#event-activate
    //      http://balaarjunan.wordpress.com/2010/11/10/html5-session-storage-key-things-to-consider/
    //
    //  Define friendly index name
    var index = 'key';
    //  Define friendly data store name
    var dataStore = window.sessionStorage;
    //  Start magic!
    try {
        // getter: Fetch previous value
        var oldIndex = dataStore.getItem(index);
    } catch(e) {
        // getter: Always default to first tab in error state
        var oldIndex = 0;
    }
    jQuery('#tabs').tabs({
        // The zero-based index of the panel that is active (open)
        active : oldIndex,
        // Triggered after a tab has been activated
        activate : function( event, ui ){
            //  Get future value
            var newIndex = ui.newTab.parent().children().index(ui.newTab);
            //  Set future value
            dataStore.setItem( index, newIndex ) 
        }
    }); 
    }); 


});