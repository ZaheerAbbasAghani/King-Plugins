jQuery(document).ready(function(){

function intervals(startString, endString) {
    var start = moment(startString, 'HH:mm');
    var end = moment(endString, 'HH:mm');

    // round starting minutes up to nearest 15 (12 --> 15, 17 --> 30)
    // note that 59 will round up to 60, and moment.js handles that correctly
    start.minutes(Math.ceil(start.minutes() / 15) * 15);

    var result = [];

    var current = moment(start);

    while (current <= end) {
        result.push(current.format('HH:mm'));
        current.add(15, 'minutes');
    }

    return result;
}


setTimeout(function(){

   jQuery("#reservation_start_time").val(jQuery(".xdsoft_timepicker.active .xdsoft_current").text());

   // Reserved Gray Spots

    var data = {
      'action': 'sqr_get_reserved_seats_table',
      'selected_date': sqr_object.todayDate
    };
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(sqr_object.ajax_url, data, function(response) {

        var status = response.status;
        if(status == 1){

            jQuery(response.result).each(function(k,v){
                var val = jQuery.trim(v);
                jQuery("."+val).parent().addClass("highlighted");
            });

        }else{

            jQuery(".sqr_wrapper td").removeClass("highlighted");
            jQuery("#reservation_start_date_time").val("");

        }

        jQuery(document).find("td.reserved").tooltip({
          show: {
            effect: "slideTop",
            delay: 250
          }
        });

        if(!jQuery("td.highlighted").hasClass("reserved")){
            jQuery(".sqr_wrapper td.highlighted").attr("title", "Click here to make reservation");
        }

    }); 

    // check if already booked blocks available.
    var data = {
        'action': 'sqr_fetch_reserved_spots',
        'table_id': jQuery(".sqr_wrapper").attr("data-table"),
        'dateToday': ""
    };
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(sqr_object.ajax_url, data, function(response) {

       // console.log(response);

        jQuery(".sqr_wrapper_top").css("opacity","0.1");
        jQuery(".adminReservationBar").append("<img src='../../wp-content/plugins/squiggz-reservation/images/loader.gif' class='sqr_loader'>");
        
        jQuery(response.results).each(function(k,v){

            var spots = v.spot_selected.split(',');
            jQuery(spots).each(function(key,value){
                
                if(value != ""){
    
                        jQuery("."+jQuery.trim(value)).parent().css("background",v.color);
                        jQuery("."+jQuery.trim(value)).parent().addClass("reserved");
                        jQuery("."+jQuery.trim(value)).parent().attr("title","Reserved: "+v.start_date+" "+v.correct_start_time+" "+v.correct_end_time);

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

}, 1000);
 

setTimeout(function(){
 // Showing only currently active blocks
    
    var start_time = jQuery(".xdsoft_timepicker.active .xdsoft_current").text();
    var data = {
      'action': 'sqr_check_empty_seats',
      'start_time': start_time,
    };
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(sqr_object.ajax_url, data, function(response) {

       // console.log(response);

        jQuery("td.highlighted").each(function(k,v){

                if( response.start_time < jQuery(this).attr("start-time") || response.start_time > jQuery(this).attr("end-time")){

                     if(Date.parse(sqr_object.todayDate)==Date.parse(jQuery("#reservation_start_date_time").val())){

                        jQuery(this).removeClass("reserved");
                        jQuery(this).attr("title","Click here to make reservation");
                        jQuery(this).css("background","");
                    }
                   
                }else{

                    
                    if(  response.start_time < jQuery(this).attr("end-time")){

                            if(Date.parse(sqr_object.todayDate)==Date.parse(jQuery("#reservation_start_date_time").val())){

                                jQuery(this).addClass("reserved");
                                jQuery(this).attr("title","Reserved: "+jQuery(this).attr("dt")+" "+jQuery(this).attr("correct-start-time")+" "+jQuery(this).attr("correct-end-time"));

                                jQuery(this).css("background",jQuery(this).attr("color"));

                            }
                    }
                }
            

            jQuery(".sqr_loader").remove();
            jQuery(".sqr_wrapper_top").css("opacity","1");

            jQuery(document).find("td.reserved").tooltip({
              show: {
                effect: "slideTop",
                delay: 250
              }
            });


        });
    });


}, 2000);


var spotName = [];
jQuery(document).on("click","td.highlighted", function(){
    if(!jQuery(this).hasClass("user_selectable") && !jQuery(this).hasClass("reserved")){
        if(jQuery("td.user_selectable").length <= 3 && !jQuery(this).hasClass("loginPlease")){
            jQuery(this).addClass("user_selectable");
            spotName.push( jQuery(this).find("span").text() );
        }

        if(jQuery("td.user_selectable").length == 1 && !jQuery(this).hasClass("loginPlease")){
            jQuery(".adminReservationBar").append("<a href='#' class='button makeReservation' style='margin-left:"+localStorage.getItem("btnLeft")+"px;'> "+sqr_object.make_reservation+" </a>");
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
            html: sqr_object.loginMessage,
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
            title: sqr_object.reservation_form_title+' <hr>',
            html: "<form method='post' autocomplete='off' action='' id='reservation_information' data-table='"+jQuery(this).parent().parent().parent().attr("data-table")+"' style='text-align:left;'><label> "+sqr_object.reservation_start_time_label+" <input type='text' name='reservation_start_time_only' id='reservation_start_time_only' value='"+ctime+"' required></label> <br> <label> "+sqr_object.reservation_end_time_label+" <input type='text' name='reservation_end_time_only' id='reservation_end_time_only' required></label> <br> <label> "+sqr_object.reservation_choose_game_label+" <select name='game' id='game' required disabled> <option value=''> --- </option></select></label> <br> <input type='submit' value='"+sqr_object.reservation_submit_button_text+"' disabled></form>",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            showConfirmButton: false,
            showCancelButton: false,
            showCloseButton: true,
            //confirmButtonText: 'Yes, delete it!'
        });

        jQuery('#reservation_start_date_only').datetimepicker({
            value:new Date("YYYY-dd-MM"),
            minDate : new Date("YYYY-dd-MM"),
            format: "Y-m-d",
            timepicker:false,
            step: 15,
            dayOfWeekStart:1
        });


        // Booking restricted before 24 hours.
        if(jQuery("#reservation_start_date_time").val() == moment().format('YYYY-MM-DD') || jQuery("#reservation_start_date_time").val() == moment().add(1,'days').format('YYYY-MM-DD')){
            var todayTimeOnly = sqr_object.todayTime;
                localStorage.setItem("minTimeOnly", todayTimeOnly);

        }else{
            var todayTimeOnly = "";
            localStorage.setItem("minTimeOnly", "00:00");
        }



        jQuery('#reservation_start_time_only').datetimepicker({
            value: jQuery("#reservation_start_time").val(), 
            dateFormat: '',
            datepicker:false,
            pickDate: false,
            format: "H:i",
            dayOfWeekStart:1,
            timeOnly:true,
            step: 15,
            onShow:function( ct, $input ){
                this.setOptions({
                    minTime: localStorage.getItem("minTimeOnly")
                });
            },
            onSelectTime:function(dp,$input){
                jQuery("#reservation_end_time_only").val($input.val());
            }
        
        });

        jQuery('#reservation_end_time_only').datetimepicker({
            value: jQuery("#reservation_start_time").val(),
            dateFormat: '',
            datepicker:false,
            pickDate: false,
            format: "H:i",
            timeOnly:true,
            dayOfWeekStart:1,
            minTime: jQuery("#reservation_start_time_only").val(),
            step: 15,
            onShow:function( ct, $input ){
                this.setOptions({
                    minTime: jQuery("#reservation_start_time_only").val()
                });
            },
            onSelectTime:function(dp,$input){
                var start_time  = jQuery("#reservation_start_time_only").val();
                var end_time    = $input.val();

                var spots = [];
                jQuery("td.user_selectable").each(function(k,v){
                    spots.push(jQuery(this).find("span").text());
                }); 

                //console.log(spots);

                var data = {
                  'action': 'sqr_double_check_date_time',
                  'startDate': jQuery("#reservation_start_date_time").val(),
                  'startTime': start_time,
                  'endTime': end_time,
                  'intervals': intervals(start_time, end_time),
                  'spots':spots
                };
                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(sqr_object.ajax_url, data, function(response) {

                    if(response.status == 0){
                        alert(response.message);
                         jQuery("#game").attr("disabled", true);
                        jQuery("input[type='submit']").attr("disabled", true);
                    }else{
                        jQuery("#game").attr("disabled", false);
                        jQuery("input[type='submit']").attr("disabled", false);
                    }
                }); 


            }
        });

});


jQuery(document).on("submit","#reservation_information", function(e){
    e.preventDefault();

    var choosenDate = jQuery("#reservation_start_date_time").val();
    var minDateDisplay = moment().add(sqr_object.sqrTime,'d').format('YYYY-MM-DD');
    var maxDateDisplay = moment().add(sqr_object.sqrDays,'d').format('YYYY-MM-DD');

    if(Date.parse(choosenDate) < Date.parse(minDateDisplay) || Date.parse(choosenDate) > Date.parse(maxDateDisplay)){

        var before = sqr_object.sqrTime > 1 ? sqr_object.sqrTime+" days" : sqr_object.sqrTime+" day";
        Swal.fire('', sqr_object.reservation_before_after_time_message,'error').then(function() {
            //location.reload();
        });
        return false;
    }


    var data = {
      'action': 'sqr_make_reservation',
      'startDate': jQuery("#reservation_start_date_time").val(),
      'startTime': jQuery("#reservation_start_time_only").val(),
      'endTime': jQuery("#reservation_end_time_only").val(),
      'game': jQuery("#game option:selected").val(),
      'reserve_game': jQuery("#game option:selected").attr("data-id"),
      'choosen_spot': spotName,
      'intervals': intervals(jQuery("#reservation_start_time_only").val(),jQuery("#reservation_end_time_only").val()),
      'color': jQuery("#game option:selected").attr("data-color"),
      'floor_id': jQuery(".sqr_wrapper").attr("data-table")
    };
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(sqr_object.ajax_url, data, function(response) {
       //console.log(response);
        if(response.status == 0){
            Swal.fire('', sqr_object.AlreadyBlocked,'error').then(function() {
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
    //value:sqr_object.todayDate,
    minDate : sqr_object.todayDate,
    format: "Y-m-d",
    timepicker:false,
    dayOfWeekStart:1,
    step: 15,
    onSelectDate:function(dp,$input){

            // check if already booked blocks available.
        var data = {
            'action': 'sqr_fetch_reserved_spots',
            'table_id': jQuery(".sqr_wrapper").attr("data-table"),
            'dateToday': $input.val()
        };
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(sqr_object.ajax_url, data, function(response) {


            jQuery("td.highlighted").removeClass("reserved");
            jQuery("td.highlighted").attr("title","Click here to make reservation");
            jQuery("td.highlighted").css("background","");


            jQuery(".sqr_wrapper_top").css("opacity","0.1");
            jQuery(".adminReservationBar").append("<img src='../../wp-content/plugins/squiggz-reservation/images/loader.gif' class='sqr_loader'>");

            // Remove Layout of table

            jQuery("table.sqr_wrapper tr").each(function(k,v){
                jQuery(this).find("td").each(function(k,v){
                    jQuery(this).removeClass("highlighted");
                });
            });


            var data = {
              'action': 'sqr_get_reserved_seats_table',
              'selected_date': $input.val()
            };
            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(sqr_object.ajax_url, data, function(response) {

                var status = response.status;
                if(status == 1){

                    jQuery(response.result).each(function(k,v){
                        var val = jQuery.trim(v);
                        jQuery("."+val).parent().addClass("highlighted");
                    });

                }else{

                    jQuery(".sqr_wrapper td").removeClass("highlighted");
                    jQuery("#reservation_start_date_time").val("");

                }

                jQuery(document).find("td.reserved").tooltip({
                  show: {
                    effect: "slideTop",
                    delay: 250
                  }
                });

                if(!jQuery("td.highlighted").hasClass("reserved")){
                    jQuery(".sqr_wrapper td.highlighted").attr("title", "Click here to make reservation");
                }

            }); 




            jQuery(response.results).each(function(k,v){

                var spots = v.spot_selected.split(',');
                jQuery(spots).each(function(key,value){
                    
                    if(value != ""){

                            jQuery("."+jQuery.trim(value)).parent().css("background",v.color);
                            jQuery("."+jQuery.trim(value)).parent().addClass("reserved");
                            jQuery("."+jQuery.trim(value)).parent().attr("title","Reserved: "+v.start_date+" "+v.correct_start_time+" "+v.correct_end_time);

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
               
        setTimeout(function(){


                var start_time = jQuery(".xdsoft_timepicker.active .xdsoft_current").text();

                var data = {
                  'action': 'sqr_check_empty_seats',
                  'start_time': start_time,
                };
                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(sqr_object.ajax_url, data, function(response) {

                   // console.log(response);

                    jQuery("td.highlighted").each(function(k,v){

                            if( response.start_time < jQuery(this).attr("start-time") || response.start_time > jQuery(this).attr("end-time")){

                                 if(Date.parse($input.val())==Date.parse(jQuery(this).attr("dt"))){

                                    jQuery(this).removeClass("reserved");
                                    jQuery(this).attr("title","Click here to make reservation");
                                    jQuery(this).css("background","");
                                }
                               
                            }else{

                                
                                if(  response.start_time < jQuery(this).attr("end-time")){

                                        if(Date.parse($input.val())==Date.parse(jQuery(this).attr("dt"))){

                                            jQuery(this).addClass("reserved");
                                            jQuery(this).attr("title","Reserved: "+jQuery(this).attr("dt")+" "+jQuery(this).attr("correct-start-time")+" "+jQuery(this).attr("correct-end-time"));
                                            jQuery(this).css("background",jQuery(this).attr("color"));

                                        }
                                }
                            }
                    });

                    jQuery(".sqr_loader").remove();
                    jQuery(".sqr_wrapper_top").css("opacity","1");

                    jQuery(document).find("td.reserved").tooltip({
                      show: {
                        effect: "slideTop",
                        delay: 250
                      }
                    });

                });

             
        }, 1000);


           jQuery(".sqr_loader").remove();
                jQuery(".sqr_wrapper_top").css("opacity","1");


            // Booking restricted before 24 hours.
            if($input.val() == moment().format('YYYY-MM-DD') ||  $input.val() == moment().add(1,'days').format('YYYY-MM-DD')){

                var todayTime = sqr_object.todayTime;
                localStorage.setItem("minTime", todayTime);

               // jQuery("#reservation_start_time").val(sqr_object.todayTime);

            }else{
                var todayTime = "";
                localStorage.setItem("minTime", "00:00");
            }



       // }); 
    }
});


// Start Time Field
jQuery('#reservation_start_time').datetimepicker({
    datepicker:false,
    pickDate: false,
    format: "H:i",
    timeOnly:true,
    dayOfWeekStart:1,
    step: 15,
    onSelectTime:function(dp,$input){

        var start_time = $input.val();
        var data = {
          'action': 'sqr_check_empty_seats',
          'start_time': start_time,
        };
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(sqr_object.ajax_url, data, function(response) {

            jQuery("td.highlighted").each(function(k,v){

                    if( response.start_time < jQuery(this).attr("start-time") || response.start_time > jQuery(this).attr("end-time")){
                        
                         if(Date.parse(jQuery(this).attr("dt"))==Date.parse(jQuery("#reservation_start_date_time").val())){

                            jQuery(this).removeClass("reserved");
                            jQuery(this).attr("title","Click here to make reservation");
                            jQuery(this).css("background","");
                        }
                       
                    }else{

                        if(  response.start_time < jQuery(this).attr("end-time")){
                            if(Date.parse(jQuery(this).attr("dt"))==Date.parse(jQuery("#reservation_start_date_time").val())){

                                jQuery(this).addClass("reserved");
                                jQuery(this).attr("title","Reserved: "+jQuery(this).attr("dt")+" "+jQuery(this).attr("correct-start-time")+" "+jQuery(this).attr("correct-end-time"));

                                jQuery(this).css("background",jQuery(this).attr("color"));

                            }
                        }else{
                             if(Date.parse(jQuery(this).attr("dt"))==Date.parse(jQuery("#reservation_start_date_time").val())){
                                
                                jQuery(this).addClass("reserved");
                                jQuery(this).attr("title","Reserved: "+jQuery(this).attr("dt")+" "+jQuery(this).attr("correct-start-time")+" "+jQuery(this).attr("correct-end-time"));
                                jQuery(this).css("background",jQuery(this).attr("color"));

                            }
                        }
                    }

            });


            jQuery(document).find("td.reserved").tooltip({
              show: {
                effect: "slideTop",
                delay: 250
              }
            });



        });
    }
});

/*jQuery('#reservation_end_time').datetimepicker({
    dateFormat: '',
    datepicker:false,
    pickDate: false,
    format: "H:i",
    timeOnly:true,
    dayOfWeekStart:1,
    defaultTime: sqr_object.todayTime,
    onShow:function( ct, $input ){
        this.setOptions({
            minTime: jQuery("#reservation_start_time").val()
        });
    }
});*/


jQuery(".reserved").attr("title","");



var oldScrollTop = jQuery(".adminReservationBar").scrollTop();
var oldScrollLeft = jQuery(".adminReservationBar").scrollLeft();

jQuery(".adminReservationBar").scroll(function () { 
    if(oldScrollTop == jQuery(".adminReservationBar").scrollTop()) {
        //console.log('Horizontal');
        jQuery(".makeReservation").css({
            "margin-left": (jQuery(".adminReservationBar").scrollLeft()) + "px"
        });

        localStorage.setItem("btnLeft",jQuery(".adminReservationBar").scrollLeft());
    }
    
    oldScrollTop = jQuery(".adminReservationBar").scrollTop();
    oldScrollLeft = jQuery(".adminReservationBar").scrollLeft();
});


jQuery('#table_id').DataTable({ 
   "order": [[ 1, "desc" ]],
   "pageLength": 15,
});

}); 

