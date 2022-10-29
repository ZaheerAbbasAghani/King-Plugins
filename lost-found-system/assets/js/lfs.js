jQuery(document).ready(function(){
jQuery("#lfs_advertisement_form").submit(function(e) {
    e.preventDefault();
  }).validate({
    rules: {
      lfs_user_name: {
        required: true,
      },
      /*lfs_user_email: {
        required: true,
      },
      lfs_phone_number: {
        required: true,
      },
      lfs_lost_or_found_date: {
        required: true,
        date:true
      },
      lfs_lost_or_found_place: {
        required: true,
      },
      lfs_state: {
        required: true,
      },
      lfs_animal_type: {
        required: true,
      },
      lfs_animal_breed: {
        required: true,
      },
      
      lfs_pictures:{
      	required:true
      },
      lfs_description:{
      	required:true
      }*/

      
    },
    messages: {
      lfs_user_name: {
        required: "Name is required.",
      },
      /*lfs_user_email: {
        required: "Email is required",
      },
      lfs_phone_number: {
        required: "Phone Number is required",
      },
      lfs_lost_or_found_date: {
        required: "Date is required",
      },
      lfs_lost_or_found_place: {
        required: "Place is required",
      },
      lfs_state: {
        required: "Place is required",
      },
      lfs_animal_type: {
        required: "Animal Type is required",
      },
      lfs_animal_breed: {
      	required: "Animal breed is required",
      },
      lfs_pictures:{
      	required: "Picture is required",
      },
      lfs_description:{
      	required: "Decription is required",
      }*/
      
      
    },
    submitHandler: function(form) {
      jQuery("#lfs_advertisement_form .lfs_frm").hide();
      jQuery(".showaftersubmit").show();
      //alert(ajax_object.ajax_url); 
      //form.preventDefault();
      var fd = new FormData(jQuery('#lfs_advertisement_form')[0]);
     
      var filesLength=document.getElementById('lfs_pictures').files.length;
      for(var i=1;i<filesLength;i++){
        fd.append("lfs_pictures_"+i, document.getElementById('lfs_pictures').files[i]);
      }

      fd.append("lfs_user_name", jQuery('#lfs_user_name').val());
      fd.append("lfs_user_email", jQuery('#lfs_user_email').val());
      fd.append("lfs_phone_number", jQuery('#lfs_phone_number').val());
      fd.append("lfs_lost_or_found_date", jQuery('#lfs_lost_or_found_date').val());
      fd.append("lfs_lost_or_found_place", jQuery('#lfs_lost_or_found_place').val());
      fd.append("lfs_state", jQuery('#lfs_state').val());
      fd.append("lfs_animal_type", jQuery('#lfs_animal_type').val());
      fd.append("lfs_animal_breed", jQuery('#lfs_animal_breed').val());
      fd.append("lfs_special_mark", jQuery('#lfs_special_mark').val());
      fd.append("lfs_birth_Day", jQuery('#lfs_birth_Day').val());
      fd.append("lfs_micro_chip", jQuery('#lfs_micro_chip').val());
      fd.append("lfs_description", jQuery('#lfs_description').val());
      
      fd.append("action", 'lfs_create_advertisement_process');
      jQuery.ajax({
        type: 'POST',
        url: ajax_object.ajax_url,
        data: fd,
        processData: false,
        contentType: false,
        success: function(data, textStatus, XMLHttpRequest) {
          console.log(data);
          /*jQuery("#lfs_advertisement_form .lfs_frm").show();
          jQuery(".showaftersubmit").hide();
          location.reload();*/
        },
        error: function(MLHttpRequest, textStatus, errorThrown) {
          alert(errorThrown);
        }
      });
      return false;
    },
  });


jQuery(".missing_contact").click(function(e){
  e.preventDefault();
  jQuery(".owner_contact").slideToggle();
});



jQuery("#missing_form").submit(function(e) {
    e.preventDefault();
  }).validate({
    rules: {
      myname: {
        required: true,
      },
      mymessage: {
        required: true,
      },
      myemail: {
        required: true,
      },
      myphone: {
        required: true,
        date:true
      },
      
    },
    messages: {
      myname: {
        required: "Name is required.",
      },
      myemail: {
        required: "Email is required",
      },
      myphone: {
        required: "Phone Number is required",
      },
      mymessage: {
        required: "Message is required",
      }
            
    },
    submitHandler: function(form) {
      jQuery("#my_response").val("Processing...");
      /*jQuery("#lfs_advertisement_form .lfs_frm").hide();
      jQuery(".showaftersubmit").show();*/
      //alert(ajax_object.ajax_url); 
      //form.preventDefault();
      var fd = new FormData(jQuery('#missing_form')[0]);
      fd.append("myname", jQuery('#myname').val());
      fd.append("mymessage", jQuery('#mymessage').val());
      fd.append("myemail", jQuery('#myemail').val());
      fd.append("myphone", jQuery('#myphone').val());
      fd.append("owner_email", jQuery('#owner_email').val());
      fd.append("action", 'lfs_contact_entity_owner');
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