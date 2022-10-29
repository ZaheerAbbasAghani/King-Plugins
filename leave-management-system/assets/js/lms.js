jQuery(document).ready(function(){
	jQuery('#date-range1').dateRangePicker(
	{

	startDate: new Date(),
	selectForward: true,
	startOfWeek: 'monday',
	separator : ' ~ ',
	format: 'YYYY-MM-DD HH:mm',
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

	jQuery('#off_form').submit(function(e) {
	   
		e.preventDefault();

		jQuery(".btn-primary").attr("disabled", true);

	    var values = jQuery(this).serialize();
	    var data = {
			'action': 'lms_create_leave_record',
			'details': values,
			'status':0,
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(lms_ajax_object.ajax_url, data, function(response) {
			alert(response);
			location.reload();
		});

	});

});


jQuery(document).on('shown.bs.tab', 'a[id="calendar-view"]', function (e) {

    var calendarEl = document.getElementById("lms-calendar");

    var today = moment().day();
    var calendar = new FullCalendar.Calendar(calendarEl, {
    	firstDay: today,
    	dayMaxEvents: true,
        events: lms_ajax_object.leave_data
    });

	calendar.render();

 });