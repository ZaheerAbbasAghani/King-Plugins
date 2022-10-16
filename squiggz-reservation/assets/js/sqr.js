jQuery(document).ready(function(){

// Check if bookable blocks available.
var d = new Date();
var today = d.getDate() >= 10 ? d.getDate() : "0"+d.getDate();
var selected_date = d.getFullYear() + "-" + (d.getMonth()+1) + "-" + today;

var data = {
  'action': 'sqr_get_reserved_seats_table',
  'selected_date': selected_date
};
// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
jQuery.post(sqr_object.ajax_url, data, function(response) {

    var status = response.status;
    if(status == 1){

        jQuery(response.result).each(function(k,v){
            var val = jQuery.trim(v);
            jQuery("."+val).parent().addClass("highlighted");
        });
        jQuery("#reservation_start_date_time").val(selected_date);

    }else{

        jQuery(".sqr_wrapper td").removeClass("highlighted");
        jQuery("#reservation_start_date_time").val("");

    }

    jQuery(document).find("td.highlighted").tooltip({
      show: {
        effect: "slideTop",
        delay: 250
      }
    });

    jQuery(".sqr_wrapper td.highlighted").attr("title", "Click here to make reservation");

}); 

// check if already booked blocks available.
var data = {
    'action': 'sqr_fetch_reserved_spots',
    'table_id': jQuery(".sqr_wrapper").attr("data-table"),
    'dateToday': ""
};
// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
jQuery.post(sqr_object.ajax_url, data, function(response) {
//    console.log(response);
    jQuery(response.results).each(function(k,v){

        var spots = v.spot_selected.split(',');
        jQuery(spots).each(function(key,value){
            
            if(value != ""){
                jQuery("."+jQuery.trim(value)).parent().css("background",v.color);
                jQuery("."+jQuery.trim(value)).parent().addClass("reserved");
                jQuery("."+jQuery.trim(value)).parent().attr("title","");
                
                jQuery("."+jQuery.trim(value)).parent().attr("start-time",v.start_time);
                jQuery("."+jQuery.trim(value)).parent().attr("end-time",v.end_time);

                jQuery("."+jQuery.trim(value)).parent().attr("correct-start-time",v.correct_start_time);
                jQuery("."+jQuery.trim(value)).parent().attr("correct-end-time",v.correct_end_time);

                jQuery("."+jQuery.trim(value)).parent().attr("color",v.color);
                jQuery("."+jQuery.trim(value)).parent().attr("dt",v.start_date);

            }

        });
    });

});


var spotName = [];
jQuery(document).on("click","td.highlighted", function(){
    if(!jQuery(this).hasClass("user_selectable") && !jQuery(this).hasClass("reserved")){
        if(jQuery("td.user_selectable").length <= 3 && !jQuery(this).hasClass("loginPlease")){
            jQuery(this).addClass("user_selectable");
            spotName.push( jQuery(this).find("span").text() );
        }

        if(jQuery("td.user_selectable").length == 1 && !jQuery(this).hasClass("loginPlease")){
            jQuery(".adminReservationBar").append("<a href='#' class='button makeReservation'> Make Reservation </a>");
        }

    }else{

        var index = spotName.indexOf(jQuery(this).find("span").text());
        
        if (index > -1) {
            spotName.splice(index, 1);
        }

        jQuery(this).removeClass("user_selectable");
        if(jQuery("td.user_selectable").length == 0){
            jQuery(".makeReservation").remove();
        }
    }
    
});

jQuery(document).on("click",".sqr_wrapper tr td", function(){

    if(jQuery(this).hasClass("loginPlease")){
        //window.location = sqr_object.login_redirect;

        Swal.fire({
            title: 'Login <hr>',
            html: "You need to login/register to make reservation",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            showConfirmButton: true,
            showCancelButton: false,
            showCloseButton: true,
            //confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
        if (result['isConfirmed']){
          window.location = "https://www.squiggz.com/mein-konto";
        }
      });


    }

});


jQuery(document).on("click",".makeReservation", function(){

        var data = {
          'action': 'sqr_fetch_games',
          'post_id': jQuery(".sqr_wrapper").attr("data-table"),
        };
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(sqr_object.ajax_url, data, function(response) {
            var seats   = response.seats; 
            var colors  = response.colors; 
            jQuery(response.games).each(function(k,v){
                  jQuery('#game').append(jQuery('<option>', { 
                      value: v,
                      text : v,
                      'data-id' : seats[k],
                      'data-color': colors[k],
                  }));
            });
        });

        var ctime = jQuery(".user_selectable").eq(0).attr("correct-start-time") == undefined ? "" : jQuery(".user_selectable").eq(0).attr("correct-start-time");

        Swal.fire({
            title: 'Reservation Information <hr>',
            html: "<form method='post' action='' id='reservation_information' data-table='"+jQuery(this).parent().parent().parent().attr("data-table")+"' style='text-align:left;'><label> Start Time <input type='text' name='reservation_start_time_only' id='reservation_start_time_only' value='"+ctime+"' required></label> <br> <label> End Time <input type='text' name='reservation_end_time_only' id='reservation_end_time_only' required></label> <br> <label> Choose a game <select name='game' id='game' required> <option value=''> Click to choose </option></select></label> <br> <input type='submit' value='Submit'></form>",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            showConfirmButton: false,
            showCancelButton: false,
            showCloseButton: true,
            //confirmButtonText: 'Yes, delete it!'
        });

        jQuery("#reservation_start_time_only").val(jQuery(".sqr_wrapper").attr("current-time"));

        var minDateDisplay = "";
        var maxDateDisplay = "";

        if(sqr_object.sqrTime == 24){
            minDateDisplay = moment().add(1,'d').format('YYYY-MM-DD');
            maxDateDisplay = moment().add(sqr_object.sqrDays,'d').format('YYYY-MM-DD');
        }else{
            minTimeDisplay = moment().add(sqr_object.sqrTime,'h').format('h:i');
            maxDateDisplay = moment().add(sqr_object.sqrDays,'d').format('YYYY-MM-DD');
        }

        jQuery('#reservation_start_time_only').datetimepicker({
            dateFormat: '',
            datepicker:false,
            pickDate: false,
            format: "H:i",
            timeOnly:true,
            minTime: jQuery(".user_selectable").eq(0).attr("correct-start-time"),
            defaultTime:'00:00',
            step: 15,
            onSelectTime:function(dp,$input){
                jQuery("#reservation_end_time_only").val($input.val());
            }
        });

        jQuery('#reservation_end_time_only').datetimepicker({
            dateFormat: '',
            datepicker:false,
            pickDate: false,
            format: "H:i",
            timeOnly:true,
            defaultTime:'00:00',
            step: 15,
            onShow:function( ct, $input ){
                this.setOptions({
                    minTime: jQuery("#reservation_start_time_only").val()
                });
            },
        });

});




jQuery(document).on("change","#game", function(e){


    var result = localStorage.getItem('choosen_spot').split(',');
  
    if(jQuery(this).find("option:selected").attr("data-id") == 1){
        localStorage.setItem('final_spots',result[0]);
    }

    if(jQuery(this).find("option:selected").attr("data-id") == 2 ){
       localStorage.setItem('final_spots',result[0]+","+result[1]);
    }

    if(jQuery(this).find("option:selected").attr("data-id") == 3){
        localStorage.setItem('final_spots',result[0]+","+result[1]+","+result[2]);
    }

    if(jQuery(this).find("option:selected").attr("data-id") == 4){
        localStorage.setItem('final_spots',result[0]+","+result[1]+","+result[2]+","+result[3]);
    }


});

jQuery(document).on("submit","#reservation_information", function(e){
    e.preventDefault();

    var data = {
      'action': 'sqr_make_reservation',
      'startDate': jQuery("#reservation_start_date_time").val(),
      'startTime': jQuery("#reservation_start_time_only").val(),
      'endTime': jQuery("#reservation_end_time_only").val(),
      'game': jQuery("#game option:selected").val(),
      'reserve_game': jQuery("#game option:selected").attr("data-id"),
      'choosen_spot': spotName,
      'color': jQuery("#game option:selected").attr("data-color"),
      'floor_id': jQuery(".sqr_wrapper").attr("data-table")
    };
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(sqr_object.ajax_url, data, function(response) {
       console.log(response);
        if(response.status == 0){
            Swal.fire('', response.message,'error').then(function() {
                //location.reload();
            });
        }else if(response.status == 2){
            Swal.fire('', response.message,'info').then(function() {
                //location.reload();
            });
        }else{
            Swal.fire('', response.message,'success').then(function() {
                location.reload();
            });
        }
    }); 
});


jQuery('#reservation_start_date_time').datetimepicker({
    minDate : new Date("YYYY-dd-MM"),
    format: "Y-m-d",
    timepicker:false,
    step: 15,
    onSelectDate:function(dp,$input){

        var data = {
          'action': 'sqr_get_reserved_seats_table',
          'selected_date': $input.val()
        };
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(sqr_object.ajax_url, data, function(response) {

            var status = response.status;
            if(status == 1){

                jQuery(".sqr_wrapper td").removeClass("highlighted");
                jQuery(".sqr_wrapper td.reserved").css("background","");
                jQuery(".sqr_wrapper td").removeClass("reserved");


                jQuery(response.result).each(function(k,v){
                    var val = jQuery.trim(v);
                    jQuery("."+val).parent().addClass("highlighted");
                });

                var data = {
                    'action': 'sqr_fetch_reserved_spots',
                    'table_id': jQuery(".sqr_wrapper").attr("data-table"),
                    'dateToday': jQuery("#reservation_start_date_time").val()
                };
                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(sqr_object.ajax_url, data, function(response) {
                    
                    jQuery(response.results).each(function(k,v){
                        var spots = v.spot_selected.split(',');
                        jQuery(spots).each(function(key,value){
                            
                            if(value != ""){

                                jQuery("."+jQuery.trim(value)).parent().css("background",v.color);
                                jQuery("."+jQuery.trim(value)).parent().addClass("reserved");
                                jQuery("."+jQuery.trim(value)).parent().attr("title","Reserved: "+v.start_date+" "+v.correct_start_time+" "+v.correct_end_time);
                                jQuery("."+jQuery.trim(value)).parent().attr("start-time",v.start_time);
                                jQuery("."+jQuery.trim(value)).parent().attr("end-time",v.end_time);
                                jQuery("."+jQuery.trim(value)).parent().attr("color",v.color);
                                jQuery("."+jQuery.trim(value)).parent().attr("dt",v.start_date);

                            }

                        });
                    });

                });

            }else{
                jQuery(".sqr_wrapper td").removeClass("highlighted");
                jQuery(".sqr_wrapper td.reserved").css("background","");
                jQuery(".sqr_wrapper td").removeClass("reserved");
            }

        }); 
    }
});

jQuery('#reservation_start_time').datetimepicker({
    timeFormat: "hh:mm",
    dateFormat: '',
    datepicker:false,
    pickDate: false,
    format: "H:i",
    timeOnly:true,
    defaultTime: "00:00",
    step: 15,
    onSelectTime:function(dp,$input){
        if($input.val() == ""){
            jQuery("#reservation_end_time").attr("disabled", true);    
        }else{
            jQuery("#reservation_end_time").attr("disabled", false);
        }
        jQuery("#reservation_end_time").val($input.val());

        var start_time = $input.val();
        var data = {
          'action': 'sqr_check_empty_seats',
          'start_time': start_time,
        };
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(sqr_object.ajax_url, data, function(response) {

            jQuery("td.highlighted").each(function(k,v){

                    if( response.start_time > jQuery(this).attr("end-time") ){
                        if(Date.parse(jQuery(this).attr("dt"))==Date.parse(jQuery("#reservation_start_date_time").val())){

                            jQuery(this).removeClass("reserved");
                            jQuery(this).attr("title","Click here to make reservation");
                            jQuery(this).css("background","");
                        }

                    }

                    if( response.start_time < jQuery(this).attr("end-time") ){
                        
                        //console.log("Right");

                        if(Date.parse(jQuery(this).attr("dt"))==Date.parse(jQuery("#reservation_start_date_time").val())){
                            
                            jQuery(this).addClass("reserved");
                            jQuery(this).attr("title","");
                            jQuery(this).css("background",jQuery(this).attr("color"));

                        }
                    }

            });

        });
    }
});

jQuery('#reservation_end_time').datetimepicker({
    dateFormat: '',
    datepicker:false,
    pickDate: false,
    format: "H:i",
    timeOnly:true,
    defaultTime: "00:00",
    onShow:function( ct, $input ){
        this.setOptions({
            minTime: jQuery("#reservation_start_time").val()
        });
    }
});


jQuery(".reserved").attr("title","");

}); 