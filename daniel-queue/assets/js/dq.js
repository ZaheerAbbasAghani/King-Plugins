jQuery(document).ready(function() {
  
  // AJAX Request to create event
  jQuery("#frm_salon").submit(function(e) {
    e.preventDefault();
  }).validate({
    rules: {
      salon_name: {
        required: true,
        //minlength:5
      },
      salon_address: {
        required: true,
        //email:true
      },
      salon_image: {
        required: true,
        //date: true
      },
      
      
    },
    messages: {
      salon_name: {
        required: "Salon name is required.",
      },
      salon_address: {
        required: "Salon address is required.",
      },
      salon_image: {
        required: "Salon image is required",
      },
      
    },
    submitHandler: function(form) {
    	var fd = new FormData(jQuery('#frm_salon')[0]);
      fd.append("salon_image", jQuery('#salon_image')[0].files[0]);
      fd.append("salon_name", jQuery('#salon_name').val());
      fd.append("salon_description", jQuery('#salon_description').val());
      fd.append("salon_address", jQuery('#salon_address').val());
      fd.append("user_id", jQuery('#user_id').val());

      fd.append("action", 'dq_create_salon_on_site');
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


jQuery(".join_queue_btn").click(function(){
  jQuery(this).attr("disabled",true);
  var user_id   = jQuery(this).attr("user_id");
  var salon_id  = jQuery(this).attr("salon_id");

  data ={
    'action':"dq_iam_in_queue",
    'user_id':user_id,
    'salon_id': salon_id
  };

  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
  jQuery.post(ajax_object.ajax_url, data, function(response) {
    alert(response);
    location.reload();
  });

});

jQuery("#remove_customer").click(function(){
  if (confirm('Are you sure?')) {
	jQuery(this).attr("disabled",true);
    var user_id   = jQuery(this).attr("user_id");
    var salon_id  = jQuery(this).attr("salon_id");

    data ={
      'action':"dq_iam_out_of_queue",
      'user_id':user_id,
      'salon_id': salon_id
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajax_object.ajax_url, data, function(response) {
      alert(response);
      location.reload();
    });
  }//endif

});

jQuery(".delete_queue_btn").click(function(){
  if (confirm('Are you sure?')) {
	jQuery(this).attr("disabled",true);
    var user_id   = jQuery(this).attr("user_id");
    var salon_id  = jQuery(this).attr("salon_id");

    data ={
      'action':"dq_iam_out_of_queue",
      'user_id':user_id,
      'salon_id': salon_id
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajax_object.ajax_url, data, function(response) {
      alert(response);
      location.reload();
    });
  }//endif

});





});