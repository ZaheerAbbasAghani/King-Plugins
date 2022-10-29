jQuery(document).ready(function(){





  // Date Picker

  jQuery("#start_date").datepicker({ 
    dateFormat: "D, M dd, yy", 
    onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 1);
            jQuery("#end_date").datepicker("option", "minDate", dt);
        }
}).datepicker("setDate", new Date());

  jQuery('#start_time').timepicker({'showDuration': true,'timeFormat': ' g:ia '});



  //Time Picker

  jQuery("#end_date").datepicker({ 
    dateFormat: "D, M dd, yy",
    onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 1);
            jQuery("#start_date").datepicker("option", "maxDate", dt);
        }

}).datepicker("setDate", new Date());

  jQuery('#end_time').timepicker({'showDuration': true,'timeFormat': ' g:ia '});



  // AJAX Request to create event

jQuery("#eventform").submit(function(e) {
    e.preventDefault();
}).validate({
  rules:{
    event_title: {
      required: true,
      //minlength:5
    },
    uploadImage: {
      required: true,
      //email:true
    },

    event_location: {
      required: true,
      //email:true
    },

    start_date: {
       	required: true,
       	date: true
    },

    start_time: {
       	required: true,
    },

    end_date: {
       	required: true,
       	date: true,
       	//greaterThan: "#start_date"   
    },

    end_time: {
       	required: true,
       	//greaterThan: "#start_time"  
    },
    
  },
  messages:{
    event_title:{
      required:"Event title is required.",
    },
    uploadImage:{
      required:"Event Image is required.",
      extension: 'jpg|jpeg|png'
      //minlength:"Your username must consist of at least 5 characters long"
    },

    event_location:{
      required:"Event Location is required.",
    },

    start_date:{
      required:"Event Start Date is required",
    },

    start_time:{
      required:"Event Start Time is required",
    },

    end_date:{
      required:"Event End Date is required",
      //greaterThan:"Proper dates are required."
    },

    end_time:{
      required:"Event End Time is required",
      //greaterThan:"Proper time is required."
      
    },


  },

  submitHandler: function (form) {
	jQuery(".create_event").hide();
    jQuery(".form-group .controls .loaders").show();
    //alert(ajax_object.ajax_url);
//    form.preventDefault();
    var fd = new FormData(jQuery('#eventform')[0]);
    fd.append( "uploadImage", jQuery('#uploadImage')[0].files[0]);
    fd.append( "action", 'bpem_event_form_response');      
    
     jQuery.ajax({
      // "action": 'bpem_event_form_response',
      //'action': 'bpem_event_form_response',
        type: 'POST',
        url: ajax_object.ajax_url,
        data: fd, 
        processData: false,
        contentType: false,

        success: function(data, textStatus, XMLHttpRequest) {
          console.log(data);
          jQuery(".bpem_success").show("slow");
          jQuery(".bpem_success").html(data);
          location.reload();
        },

        error: function(MLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
        }

    });



/*    jQuery(this).hide();
    jQuery(".loaders").show();*/


return false;
    },
});




jQuery('.bp-events').each(function (index, value) { 



jQuery('#attend_'+index).click(function(){



    jQuery(this).hide();

    jQuery(".ghoom_"+index).show();



    var event_id  = jQuery(this).attr('data-id');

    //var user_id   = jQuery(this).attr('data-user-id');



    data = {

      'action'  : 'bpem_persons_who_attend_event',

      'event_id': event_id,

      //'user_id' : user_id,

    }





    jQuery.post(ajax_object.ajax_url, data, function(response) {



     console.log(response);

     location.reload();







    });















  });







});



});





/*jQuery(document).ready(function(){

  var mediaUploader;



  jQuery("#uploadImage").on('click',function(e){

  e.preventDefault();

    if(mediaUploader){

      mediaUploader.open();

      return;

    } //endif



    mediaUploader = wp.media.frames.file_frame = wp.media({

        title : 'Choose a Event Image',

        button: {

          text: 'Choose Image'

        },

        multiple:false,

         library: { 

           type: 'image' // limits the frame to show only images

        },

    });



    mediaUploader.on('select', function(){

      attachment = mediaUploader.state().get('selection').first().toJSON();

      jQuery('#EventUploadImage').val(attachment.url);

      jQuery(".imgshow").css({"background-image":"url("+attachment.url+")","width":"400px","height":"300px","margin":"10px 0px", "border":"6px solid #ddd","background-size":"100% 100%"});

    });



    mediaUploader.open();



  });







});
*/


jQuery(document).ready(function(jQuery)

{

    if( jQuery('ul').hasClass('sub-menu') )

    {

        var parent = jQuery('ul .sub-menu').parent();

        jQuery('ul .sub-menu').addClass('dropdown-menu');

        parent.addClass('dropdown');

        parent.children(':first-child').attr({'href': '#', 'class': 'dropdown-toggle', 'data-toggle': 'dropdown'});



    }



   /* jQuery.noConflict();

    jQuery(".event-nav").pagination({

      items: 1,

      itemsOnPage: 1

    });*/





    jQuery(function(jQuery) {

    var pageParts = jQuery("#Upcoming .bp-events");

    var numPages = pageParts.length;



    if(numPages >= 10){



      var perPage = 10;

      pageParts.slice(perPage).hide();

      jQuery(".event-nav").pagination({

          items: numPages,

          itemsOnPage: perPage,

          prevText:"",

          nextText:"",

          cssStyle: "compact-theme",

          onPageClick: function(pageNum) {

              var start = perPage * (pageNum - 1);

              var end = start + perPage;

              pageParts.hide()

              .slice(start, end).show();

          }

      });

  

    }//endif



    });



    // Upcoming Pagination ends





    jQuery(function(jQuery) {

    var pageParts = jQuery("#Past .bp-events");

    var numPages = pageParts.length;



    if(numPages >= 10){



      var perPage = 10;

      pageParts.slice(perPage).hide();

      jQuery("#Past .event-nav").pagination({

          items: numPages,

          itemsOnPage: perPage,

          prevText:"",

          nextText:"",

          cssStyle: "compact-theme",

          onPageClick: function(pageNum) {

              var start = perPage * (pageNum - 1);

              var end = start + perPage;

              pageParts.hide()

              .slice(start, end).show();

          }

      });

  

    }//endif



    });











});







function openCity(evt, eventName) {

  var i, x, tablinks;

  x = document.getElementsByClassName("eventcontainer");

  //jQuery(".eventcontainer:first-child").css({"display":"block"});

  for (i = 0; i < x.length; i++) {

    x[i].style.display = "none";

  }

  tablinks = document.getElementsByClassName("tablink");

  for (i = 0; i < x.length; i++) {

    tablinks[i].className = tablinks[i].className.replace(" active", ""); 

  }

  document.getElementById(eventName).style.display = "block";

  evt.currentTarget.className += " active";

}





//  Leave Event

jQuery(document).ready(function() {



  jQuery('.interestedbtn').click(function(){



  if(confirm("Are you sure you want to leave?")){



   /* jQuery(this).hide();

    jQuery('.interested .loaderss').show();*/



    var event_id  = jQuery(this).attr('data-id');

    //var user_id   = jQuery(this).attr('data-user-id');

    



  data = {

    'action'  : 'bpem_leave_event',

    'event_id': event_id,

    //'user_id' : user_id,

  }





  jQuery.post(ajax_object.ajax_url, data, function(response) {

    console.log(response);

    location.reload();

  });



} //end confirm



  });

});



// Remove attendy

jQuery(document).ready(function() {

    jQuery(".remove_attendy").click(function(e){

      e.preventDefault();

  if(confirm("Are You Sure! Delete Attendee ?")){

      var user = jQuery(this).attr('user-id');

      var event = jQuery(this).attr('event-id');

      jQuery(this).closest('.box').hide('slow');

      //console.log(user+' '+event);



      data = {

        'action' : 'bpem_remove_attendy',

        'user_id' : user,

        'event_id': event,

      }



      jQuery.post(ajax_object.ajax_url, data, function(response) {

        console.log(response);

      location.reload();

      });



  }



    });

});  