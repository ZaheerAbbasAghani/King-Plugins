jQuery(document).ready(function() {

	// Jquery Ui Tabs
	jQuery('#tabs').tabs({
	  active: localStorage.getItem("currentIdx"),
	  activate: function(event, ui) {
	      localStorage.setItem("currentIdx", jQuery(this).tabs('option', 'active'));
	      //alert();

		var calendarEl = document.getElementById("vir-calendar");
	    var today = moment().day();
	    var calendar = new FullCalendar.Calendar(calendarEl, {
	    	firstDay: today,
	    	dayMaxEvents: true,
	        events: vir_ajax_object.Scheduled
	    });
		calendar.render();

	  },
	  
	});


	// Date Range Picker
	jQuery('#date-range1').dateRangePicker(
	{
		startDate: new Date(),
		selectForward: true,
		startOfWeek: 'monday',
		separator : ' to ',
		format: 'YYYY-MM-DD HH:mm',
		windowResizeDelay: 100,
		autoClose: false,
		time: {
			enabled: true
		},
		showDateFilter: function(time, date)
		{
			return '<div style="padding:0 5px;">\
						<span style="font-weight:bold">'+date+'</span>\
					</div>';
		},
	});


	// Request schedule form submit
	jQuery('#requestSchedule').submit(function(e) {
	   
		e.preventDefault();

		jQuery(".btn-primary").attr("disabled", true);

	    var values = jQuery(this).serialize();
	    var data = {
			'action': 'vir_create_schedule',
			'details': values,
			'status':0,
		};
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(vir_ajax_object.ajax_url, data, function(response) {
			alert(response);
			location.reload();
		});
	});

	// AutoComplete Username Email
	var availableTags = vir_ajax_object.employees;
    jQuery( ".vir_auto" ).autocomplete({
     	source: availableTags,
        select: function (event, ui) {  

        var data = {
			'action': 'vir_get_user_email',
			'label': ui.item.label,
		};
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(vir_ajax_object.ajax_url, data, function(response) {
			jQuery("#InputYourEmail").val(response);
		});

        }
    });

    // Delete scheduled request
	jQuery(".delete_row").on("click", function(e){
	   	if(confirm("Are you sure?")){
			e.preventDefault();

			jQuery(this).parent().parent().addClass("alert alert-danger");

		    var data = {
				'action': 'vir_delete_row',
				'id': jQuery(this).attr("data-id"),
			};
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(vir_ajax_object.ajax_url, data, function(response) {
				alert(response);
				location.reload();
			});
		}else{
			return false;
		}
	});


	// Deny scheduled request
	jQuery(".deny_row").on("click", function(e){
	   	if(confirm("Are you sure?")){
			e.preventDefault();

			jQuery(this).parent().parent().addClass("alert alert-danger");

		    var data = {
				'action': 'vir_deny_schedule_request',
				'id': jQuery(this).attr("data-id"),
				'user_id': jQuery(this).parent().parent().attr("data-id"),
			};
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(vir_ajax_object.ajax_url, data, function(response) {
				alert(response);
				location.reload();
			});
		}else{
			return false;
		}
	});


	// Cover scheduled request
	jQuery(".cover_request").on("click", function(e){
	   	if(confirm("Are you sure?")){
			e.preventDefault();

			jQuery(this).parent().parent().addClass("alert alert-success");
			var displayName=jQuery(this).parent().parent().find("displayName").text();

		    var data = {
				'action': 'vir_cover_schedule_request',
				'id': jQuery(this).attr("data-id"),
				'user_id': jQuery(this).parent().parent().attr("data-id"),
				'displayName':displayName,
			};
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(vir_ajax_object.ajax_url, data, function(response) {
				alert(response);
				location.reload();
			});
		}else{
			return false;
		}
	});

   


});