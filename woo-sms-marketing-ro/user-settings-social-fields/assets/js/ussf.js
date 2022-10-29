jQuery(document).ready(function() {



jQuery(".handle_submit").click(function(e){

e.preventDefault();

  jQuery(this).attr("disabled",true);

  var social_handler = jQuery(this).parent().find("input[type='text']").attr("class");
  var social_handler_url=jQuery(this).parent().find("input[type='text']").val();
  jQuery("."+social_handler).after("<p class='jpamessage'>"+social_handler_url+"</p>");
  jQuery("."+social_handler).text(social_handler_url);

  var data = {

    'action': 'ussf_insert_social_handle',
    'social_handler': social_handler,
    'social_handler_url': social_handler_url,

  };

  // We can also pass the url value separately from ajaxurl for front end AJAX implementations

  jQuery.post(ajax_object.ajax_url, data, function(response) {

    console.log("Done");

  });



});


});