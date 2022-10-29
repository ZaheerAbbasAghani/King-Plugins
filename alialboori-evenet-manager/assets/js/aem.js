jQuery(document).ready(function(){

	var startDate = jQuery('#start_date').text();
	var endDate = jQuery('#end_date').text();

	jQuery("#aem_date_calendar").attr("min",startDate);
	jQuery("#aem_date_calendar").attr("max",endDate);

	jQuery("#aem_join_event").click(function(e){
		e.preventDefault();
		jQuery(".aem_liteBox").fadeIn("slow");
	});

	jQuery(".aem_remove_liteBox").click(function(e){
		e.preventDefault();
		jQuery(".aem_liteBox").fadeOut("slow");
	});

jQuery(document).ready(function(){
  // AJAX Request to create event
  jQuery("#eventform").submit(function(e) {
    e.preventDefault();
  }).validate({
    rules: {
      aem_email: {
        required: true,
      },
      aem_date_calendar: {
        required: true,
        date: true
      },   
    },
    messages: {
      aem_email: {
        required: "Enter your email address",
      },
      aem_date_calendar: {
        required: "Enter reminder dates",
      },
    },
    submitHandler: function(form) {
    	var fd = new FormData(jQuery('#eventform')[0]);
      	fd.append("aem_email", jQuery('#aem_email').val());
      	fd.append("aem_date_calendar", jQuery('#aem_date_calendar').val());
      	fd.append("aem_join_event", jQuery('#aem_join_event').attr("post-id"));
      
      fd.append("action", 'aem_set_reminder_for_user');
      jQuery.ajax({
        type: 'POST',
        url: ajax_object.ajax_url,
        data: fd,
        processData: false,
        contentType: false,
        success: function(data, textStatus, XMLHttpRequest) {
          alert(data);
          location.reload();
        },
        error: function(MLHttpRequest, textStatus, errorThrown) {
          alert(errorThrown);
        }
      });
      return false;
    },
  });


});

});