(function( jQuery ) {

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

// Check if bookable blocks available.
var selected_date = sqr_ajax_object.todayDate;

var data = {
  'action': 'sqr_get_reserved_seats_table',
  'selected_date': selected_date
};
// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {

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
jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {

    jQuery(response.results).each(function(k,v){

        var spots = v.spot_selected.split(',');
        jQuery(spots).each(function(key,value){
            
            if(value != ""){

              //  if(v.correct_end_time > moment().format('H:i')){

                    jQuery("."+jQuery.trim(value)).parent().css("background",v.color);
                    jQuery("."+jQuery.trim(value)).parent().addClass("reserved");
                    jQuery("."+jQuery.trim(value)).parent().attr("title","Reserved: "+v.start_date+" "+v.correct_start_time+" "+v.correct_end_time);
                    jQuery("."+jQuery.trim(value)).parent().attr("start-time",v.start_time);
                    jQuery("."+jQuery.trim(value)).parent().attr("end-time",v.end_time);
                    jQuery("."+jQuery.trim(value)).parent().attr("correct-start-time",v.correct_start_time);
                    jQuery("."+jQuery.trim(value)).parent().attr("correct-end-time",v.correct_end_time);
                    jQuery("."+jQuery.trim(value)).parent().attr("color",v.color);
                    jQuery("."+jQuery.trim(value)).parent().attr("dt",v.start_date);

           //     }
            }
        });
    });
});

}, 1000);


    // Add Color Picker to all inputs that have 'color-field' class
    jQuery(function() {
        jQuery('.sqr_game_color').each(function(){
            jQuery(this).wpColorPicker();
        });

    });

     jQuery("#addRow").click(function () {
        var html = '';
        html += '<div class="gameBlock"> <label> Game Name: <input type="text" name="sqr_game_name[]" value="" placeholder="Enter total seats available" style="width: 100%;margin-top: 6px;padding: 3px 10px;"/></label><br><br><label> Seats to fill: <input type="text" name="seats_to_fill[]" value="" placeholder="Enter seats to full when user make reservation" style="width: 100%;margin-top: 6px;padding: 3px 10px;"/></label><br><br><label> Game Color: </label><br><br> <input type="text" name="sqr_game_color[]" class="sqr_game_color" value=""/><br><br><button id="removeRow" type="button" class="button">Remove</button></div>';
        jQuery('#newRow').append(html);

        jQuery('.sqr_game_color').each(function(){
            jQuery(this).wpColorPicker();
        });

    });

    // remove row
    jQuery(document).on('click', '#removeRow', function () {
        
        if (confirm('Are you sure?')) {
            jQuery(this).closest('.gameBlock').remove();
        }else{
            return false;
        }

    });


    jQuery('#reservationTable').DataTable({ 
       "order": [[ 6, "desc" ]],
       "pageLength": 15,
    });


    jQuery(document).on("change", "#permission_needed", function(){
        
        if (confirm('Are you sure?')) {

            var val = jQuery(this).val();
            var row_id = jQuery(this).find("option:selected").attr("data-id");
            var data = {
                'action': 'sqr_permission_needed',
                'val': val,
                'row_id':row_id
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {
                alert(response);
                location.reload();
            });

        }else{
            return false;
        }
    });


    if(!jQuery(this).hasClass("reserved")){
        jQuery("table.sqr_wrapper tr td").tooltip({
          show: {
            effect: "slideTop",
            delay: 250
          }
        });
    }


jQuery(document).on("click",".makeReservation", function(){

        var data = {
          'action': 'sqr_fetch_games',
          'post_id': jQuery(".sqr_wrapper").attr("data-table"),
        };
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {
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
            title: sqr_ajax_object.reservation_form_title+' <hr>',
            html: "<form method='post' autocomplete='off' action='' id='reservation_information' data-table='"+jQuery(this).parent().parent().parent().attr("data-table")+"' style='text-align:left;'><label> "+sqr_ajax_object.reservation_start_time_label+" <input type='text' name='reservation_start_time_only' id='reservation_start_time_only' value='"+ctime+"' required></label> <br> <label> "+sqr_ajax_object.reservation_end_time_label+" <input type='text' name='reservation_end_time_only' id='reservation_end_time_only' required></label> <br> <label> "+sqr_ajax_object.reservation_choose_game_label+" <select name='game' id='game' required disabled> <option value=''> --- </option></select></label> <br> <input type='submit' value='"+sqr_ajax_object.reservation_submit_button_text+"' class='button button-primary' disabled></form>",
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
            var todayTimeOnly = sqr_ajax_object.todayTime;
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
                jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {

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


var spotName = [];
jQuery(document).on("click",".makeReservationNow td.highlighted", function(){
    if(!jQuery(this).hasClass("user_selectable") && !jQuery(this).hasClass("reserved")){
       // if(jQuery("td.user_selectable").length <= 3 && !jQuery(this).hasClass("loginPlease")){
            jQuery(this).addClass("user_selectable");
            spotName.push( jQuery(this).find("span").text() );
    //    }

        if(jQuery("td.user_selectable").length == 1 && !jQuery(this).hasClass("loginPlease")){
            jQuery(".adminReservationBar").append("<a href='#' class='button makeReservation' style='margin-left:"+localStorage.getItem("btnLeft")+"px;'> "+sqr_ajax_object.make_reservation+" </a>");
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

jQuery(document).on("submit","#reservation_information", function(e){
    e.preventDefault();

   /* var choosenDate = jQuery("#reservation_start_date_time").val();
    var minDateDisplay = moment().add(sqr_ajax_object.sqrTime,'d').format('YYYY-MM-DD');
    var maxDateDisplay = moment().add(sqr_ajax_object.sqrDays,'d').format('YYYY-MM-DD');

    console.log(minDateDisplay);
    console.log(maxDateDisplay);

    if(Date.parse(choosenDate) < Date.parse(minDateDisplay) || Date.parse(choosenDate) > Date.parse(maxDateDisplay)){

        var before = sqr_ajax_object.sqrTime > 1 ? sqr_ajax_object.sqrTime+" days" : sqr_ajax_object.sqrTime+" day";
        Swal.fire('', sqr_ajax_object.reservation_before_after_time_message,'error').then(function() {
            //location.reload();
        });
        return false;
    }*/


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
    jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {
      // console.log(response);
        if(response.status == 0){
            Swal.fire('', sqr_ajax_object.AlreadyBlocked,'error').then(function() {
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


jQuery("#table_id tr").each(function(k,v){

    jQuery('.sqr_startDate_'+k).datetimepicker({
        minDate : new Date("YYYY-dd-MM"),
        minTime : new Date("hh-mm"),
        dayOfWeekStart:1,
        step: 15,
        onChangeDateTime:function(dp,jQueryinput){
            jQuery('.sqr_endDate_'+k).val(jQueryinput.val());
        }
     });
     jQuery('.sqr_endDate_'+k).datetimepicker({

        minDate : jQuery('.sqr_startDate_'+k).val(),
        minTime : jQuery('.sqr_startDate_'+k).val().split(" ")[1],
        dayOfWeekStart:1,
        step: 15,
            onShow:function( ct ){
                this.setOptions({
                    minDate:jQuery('.sqr_startDate_'+k).val()?jQuery('.sqr_startDate_'+k).val():false,
                    minTime : jQuery('.sqr_startDate_'+k).val().split(" ")[1]
                });
            },
            onChangeDateTime:function(dp,jQueryinput){
                var data = {
                  'action': 'sqr_change_dates',
                  'startDate': jQuery('.sqr_startDate_'+k).val(),
                  'endDate': jQueryinput.val(),
                  'row_id': jQuery(".row_"+k).attr("data-id"),
                };
                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {
                   console.log(response);
                });
            }
    });

});



jQuery(".makeReserve").on("click", function(){
    jQuery(".adminReservationBar").slideToggle();
    jQuery(".standard_floor_plan").hide();
    jQuery("#rereserveNowser").removeClass("standardFloor");
    jQuery("#rereserveNowser").addClass("makeReservationNow");

    if(jQuery.trim(jQuery(this).text()) == 'Reserve a Seat'){
        jQuery(this).text("Close Reservation");
        //jQuery(".adminReservationBar").append("<a href='#' class='button makeReservation'> Make Reservation </a>");
    }else{  
        jQuery(this).text("Reserve a Seat");
        jQuery(".makeReservation").remove();
        jQuery(".standard_floor_plan").fadeIn();
    }

});


/*jQuery(document).on("click", ".editit", function(e){

    e.preventDefault(); 

    var data = {
      'action': 'sqr_edit_reserve_information',
      'row_id': jQuery(this).attr("data-id"),
    };
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {

        Swal.fire({
            title: 'Reservation Information <hr>',
            html: "<form method='post' action='' id='reservation_information_update' data-table='"+response.id+"' style='text-align:left;'><label> Start Date <input type='text' name='reservation_start_date_time' id='reservation_start_date_time' value='"+response.start_date+"' required></label> <br><label> Start Time <input type='text' name='reservation_start_time' id='reservation_start_time' value='"+response.start_time+"' required></label> <br><label> Start Time <input type='text' name='reservation_end_time' id='reservation_end_time' value='"+response.end_time+"' required></label> <br> <input type='submit' value='Submit' class='button button-primary'></form>",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            showConfirmButton: false,
            showCancelButton: false,
            showCloseButton: true,
            
        });

        jQuery('#reservation_start_date_time').datetimepicker({
            minDate : new Date("YYYY-dd-MM"),
            minTime : new Date("hh-mm"),
            formatDate:'d.m.Y h:i',
            dayOfWeekStart:1,
            step: 15,
            onChangeDateTime:function(dp,jQueryinput){
                jQuery("#reservation_end_date_time").val(jQueryinput.val());
            }
         });
         jQuery('#reservation_end_date_time').datetimepicker({
            minDate : new Date("YYYY-dd-MM"),
            minTime : new Date("hh-mm"),
            dayOfWeekStart:1,
            step: 15,

        });

    });

});*/



jQuery(document).on("submit","#reservation_information_update", function(e){
    e.preventDefault();
    var data = {
      'action': 'sqr_update_reserve_information',
      'startDateTime': jQuery("#reservation_start_date_time").val(),
      'startTime': jQuery("#reservation_start_time").val(),
      'endTime': jQuery("#reservation_end_time").val(),
      'row_id': jQuery(this).attr("data-table")
    };
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {

        Swal.fire('', response,'success').then(function() {
            location.reload();
        });
    }); 
});

jQuery(document).on("click",".deleteit", function(e){
    e.preventDefault();

    if (confirm('Are you sure? You want to delete.')) {
        jQuery(this).parent().parent().css("background","red");
        var data = {
          'action': 'sqr_delete_reserve_information',
          'row_id': jQuery(this).attr("data-id")
        };
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {
            Swal.fire('', response,'success').then(function() {
                location.reload();
            });
        }); 

    }else{
        return false;
    }
});


jQuery('#reservation_start_date_time').datetimepicker({
    value:sqr_ajax_object.todayDate,
    minDate : sqr_ajax_object.todayDate,
    format: "Y-m-d",
    timepicker:false,
    dayOfWeekStart:1,
    step: 15,
    onSelectDate:function(dp,$input){

        jQuery("#reservation_start_time").val(sqr_ajax_object.todayTime);

        var data = {
          'action': 'sqr_get_reserved_seats_table',
          'selected_date': $input.val()
        };
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {

            jQuery(".highlighted").removeClass("user_selectable");
            jQuery(".makeReservation").remove();

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
                jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {
                    
                    jQuery(response.results).each(function(k,v){
                        var spots = v.spot_selected.split(',');
                        jQuery(spots).each(function(key,value){
                            

                            if(v.correct_end_time > sqr_ajax_object.todayTime){

                                jQuery("."+jQuery.trim(value)).parent().css("background",v.color);
                                jQuery("."+jQuery.trim(value)).parent().addClass("reserved");
                                jQuery("."+jQuery.trim(value)).parent().attr("title","Reserved: "+v.start_date+" "+v.correct_start_time+" "+v.correct_end_time);
                                jQuery("."+jQuery.trim(value)).parent().attr("start-time",v.start_time);
                                jQuery("."+jQuery.trim(value)).parent().attr("end-time",v.end_time);

                                jQuery("."+jQuery.trim(value)).parent().attr("correct-start-time",v.correct_start_time);
                                jQuery("."+jQuery.trim(value)).parent().attr("correct-end-time",v.correct_end_time);

                                jQuery("."+jQuery.trim(value)).parent().attr("color",v.color);
                                jQuery("."+jQuery.trim(value)).parent().attr("dt",v.start_date);

                            }else{

                                jQuery("."+jQuery.trim(value)).parent().css("background","");
                                jQuery("."+jQuery.trim(value)).parent().removeClass("reserved");
                                jQuery("."+jQuery.trim(value)).parent().attr("title","Click here to make reservation");
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


            // Booking restricted before 24 hours.
            if($input.val() == moment().format('YYYY-MM-DD') ||  $input.val() == moment().add(1,'days').format('YYYY-MM-DD')){

                var todayTime = sqr_ajax_object.todayTime;
                localStorage.setItem("minTime", todayTime);

                jQuery("#reservation_start_time").val(sqr_ajax_object.todayTime);

            }else{
                var todayTime = "";
                localStorage.setItem("minTime", "00:00");
            }



        }); 
    }
});


// Start Time Field
jQuery('#reservation_start_time').datetimepicker({
    dateFormat: '',
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
        jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {

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
        });
    }
});

jQuery('#reservation_end_time').datetimepicker({
   dateFormat: '',
    datepicker:false,
    pickDate: false,
    format: "H:i",
    timeOnly:true,
    dayOfWeekStart:1,
    defaultTime: sqr_ajax_object.todayTime,
    onShow:function( ct, $input ){
        this.setOptions({
            minTime: jQuery("#reservation_start_time").val()
        });
    }
});


jQuery(document).on("submit", "#rereserveNowser", function(e){
    e.preventDefault();

    var booked_spot = [];
    jQuery("td.highlighted").each(function(){
        booked_spot.push(jQuery(this).find("span").text());
    });
    var dt = jQuery("#reservation_start_date_time").val();
    var rst = jQuery("#reservation_start_time").val();
    var ret = jQuery("#reservation_end_time").val();

    var data = {
      'action': 'sqr_make_it_reservable',
      'booked_spot': booked_spot,
      'dt':dt,
      'start_time':rst,
      'end_time':ret,
    };
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {

        //  console.log(response);

        Swal.fire('', response,'success').then(function() {
            location.reload();
        });
    }); 
});

jQuery(document).on("click", ".createPlan", function(e){
    e.preventDefault();



    Swal.fire({
        title: 'Create Standard Floor Plan <hr>',
        html: "<form method='post' autocomplete='off' action='' id='floor_plan' style='text-align:left;'><label> Start Date <input type='text' name='reservation_start_date_time' id='reservation_start_date_time' class='floor_plan_start_date' value='' required></label> <br><label> End Date  <input type='text' name='reservation_end_date_time' id='reservation_end_date_time' class='floor_plan_end_date' value=''></label> <br> <input type='submit' value='Submit' class='button button-primary'></form>",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        showConfirmButton: false,
        showCancelButton: false,
        showCloseButton: true,
        
    });


    jQuery('.floor_plan_start_date').datetimepicker({
        value: jQuery("#reservation_start_date_time").val(),
        format: "Y-m-d",
        timepicker:false,
        dayOfWeekStart:1,
        step: 15,
    });

    jQuery('.floor_plan_end_date').datetimepicker({
        value : jQuery(".floor_plan_start_date").val(),
        format: "Y-m-d",
        dayOfWeekStart:1,
        timepicker:false,
        step: 15,
        onShow:function( ct ){
            this.setOptions({
                minDate:jQuery('.floor_plan_start_date').val(),
            });
        },


    });
});


jQuery(document).on("submit","#floor_plan", function(e){
    e.preventDefault();
   


    var highlighted = [];
    jQuery("td.highlighted").each(function(){
       highlighted.push(jQuery(this).find("span").text());
    });

    jQuery("td.blocked").each(function(){
       highlighted.push(jQuery(this).find("span").text());
    });


    var data = {
      'action': 'sqr_floor_plan_maker',
      'startDate': jQuery(".floor_plan_start_date").val(),
      'endDate': jQuery(".floor_plan_end_date").val(),
      'highlighted': highlighted,
    };
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {

        Swal.fire('', response,'success').then(function() {
            location.reload();
        });
    }); 
});



jQuery(".reserved").attr("title","");


jQuery(".standard_floor_plan").on("click", function(){

    jQuery("#rereserveNowser").addClass("standardFloor");
    jQuery("#rereserveNowser").removeClass("makeReservationNow");
    jQuery(".makeReserve").hide();

    jQuery(".adminReservationBar").slideToggle();
    if(jQuery.trim(jQuery(this).text()) == 'Create Standard Floor Plan'){
        jQuery(this).text("Close");
        jQuery(".adminReservationBar").append("<a href='#' class='button createPlan'> Create a plan </a>");
        jQuery(".adminReservationBar").append("<a href='#' class='button deletePlan' style='margin-left:10px; margin-bottom:10px;'> Delete a plan </a>");
        
        jQuery(".sqr_wrapper tr").each(function(k,v){
            jQuery(this).find("td").each(function(){
                jQuery("#reservation_start_time").remove();
            });
        });

    }else{  
        jQuery(this).text("Create Standard Floor Plan");
        jQuery(".createPlan").remove();
        jQuery(".deletePlan").remove();
        jQuery(".makeReserve").fadeIn();
    }
});

jQuery(document).on("click", ".deletePlan", function(e){

    e.preventDefault();


     Swal.fire({
        title: 'Delete Standard Floor Plan <hr>',
        html: "<form method='post' autocomplete='off' action='' id='floor_plan_delete' style='text-align:left;'><label> Start Date <input type='text' name='reservation_start_date_time' id='reservation_start_date_time' class='floor_plan_start_date' value='' required></label> <br><label> End Date  <input type='text' name='reservation_end_date_time' id='reservation_end_date_time' class='floor_plan_end_date' value=''></label> <br> <input type='submit' value='Submit' class='button button-primary'></form>",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        showConfirmButton: false,
        showCancelButton: false,
        showCloseButton: true,
        
    });

    
    jQuery('.floor_plan_start_date').datetimepicker({
        value: jQuery("#rereserveNowser #reservation_start_date_time").val(),
        format: "Y-m-d",
        timepicker:false,
        step: 15,
        dayOfWeekStart:1,
    });

    jQuery('.floor_plan_end_date').datetimepicker({
        //value: jQuery("#rereserveNowser #reservation_start_date_time").val(),
        format: "Y-m-d",
        timepicker:false,
        step: 15,
        dayOfWeekStart:1,
    });

});


jQuery(document).on("submit","#floor_plan_delete", function(e){
    e.preventDefault();

    var highlighted = [];
    jQuery("td.highlighted").each(function(){
        if(!jQuery(this).hasClass("blocked")){
            highlighted.push(jQuery(this).find("span").text());
        }
    });

    if(!jQuery("td.highlighted").hasClass("blocked")){
        var highlighted = "";
    }

    var data = {
      'action': 'sqr_floor_plan_delete',
      'startDate': jQuery(".floor_plan_start_date").val(),
      'endDate': jQuery(".floor_plan_end_date").val(),
      'highlighted': highlighted,
    };
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {
      //     console.log(response);
        Swal.fire('', response,'success').then(function() {
            location.reload();
        });
    }); 
});


jQuery(function () {
  var isMouseDown = false,
    isHighlighted;
    jQuery('body').on('mousedown', '.standardFloor tr td', function() {
    //jQuery(".standardFloor tr td").mousedown(function () {

          isMouseDown = true;
          jQuery(this).toggleClass("blocked");
          isHighlighted = jQuery(this).hasClass("blocked");
          return false; // prevent text selection

    });

    jQuery('body').on('mouseover', '.standardFloor tr td', function() {
    //.mouseover(function () {
            if (isMouseDown) {
                jQuery(this).toggleClass("blocked", isHighlighted);
            }
    });


    jQuery(document).mouseup(function () {
            isMouseDown = false;
    });
});    


// Delete All Reservations

jQuery(document).on("click",".delete_all_reservations", function(e){
    e.preventDefault();


    if (confirm('Are you sure? You want to remove all reservations.')) {
            var data = {
              'action': 'sqr_delete_all_reservations',
              'delete': 1
            };
            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(sqr_ajax_object.ajax_url, data, function(response) {
           //       console.log(response);
                Swal.fire('', response,'error').then(function() {
                    location.reload();
                });
            }); 

    }else{
        return false;
    }


   
});


     
})( jQuery );