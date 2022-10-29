jQuery(document).ready(function(){



jQuery(".aem_delete").click(function(e){

  e.preventDefault();

    var tr = jQuery(this).parents("tr").attr("class");

    if (confirm("Confirm Delete?")) {

      var aem_email = jQuery("."+tr).find(".aem_email").text();

      var aem_calendar = jQuery("."+tr).find(".aem_calendar").text();

      var aem_post_id = jQuery("."+tr).find(".aem_delete").attr("post-id");

      

      var data = {

        'action': 'aem_remove_reminders',

        'aem_email': aem_email,

        'aem_calendar': aem_calendar,

        'aem_post_id':aem_post_id

      };

      

      // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php

      jQuery.post(ajax_object.ajax_url, data, function(response) {

          alert(response);
          location.reload();
      });

    }

})



});