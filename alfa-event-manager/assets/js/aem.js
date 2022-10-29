jQuery(document).ready(function(){

// Counter
jQuery(".bx").each(function(index,value){
  //console.log(index);
var enddate = jQuery(this).find(".enddate").text();
// Set the date we're counting down to
var countDownDate = new Date(enddate).getTime();
  // Update the count down every 1 second
  var x = setInterval(function() {
    // Get today's date and time
    var now = new Date().getTime();
    // Find the distance between now and the count down date
    var distance = countDownDate - now;
    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    // Display the result in the element with id="demo"

    jQuery(".counter_"+index).html("<div class='aem_dt'> "+days + "d </div><div class='aem_dt'>" + hours + "h </div><div class='aem_dt'>"+ minutes + "m </div><div class='aem_dt'>" + seconds + "s </div>");
    

    // If the count down is finished, write some text
    if (distance < 0) {
      clearInterval(x);
      jQuery(this).find(".counter_"+index).html("<div class='aem_dt'> EXPIRED </div>");
      //document.getElementById("demo").innerHTML = "EXPIRED";
    }
  }, 1000);
});


// Join Event
jQuery(".join_now").click(function(e){
  e.preventDefault();
  var post_id = jQuery(this).attr("post-id");
  var data = {
    'action': 'aem_join_event_process',
    'post_id': post_id
  };
  // We can also pass the url value separately from ajaxurl for front end AJAX implementations
  jQuery.post(ajax_object.ajax_url, data, function(response) {
      alert(response);
      location.reload();
  });
});

// Leave Event
jQuery(".leave_now").click(function(e){
  e.preventDefault();
  var post_id = jQuery(this).attr("post-id");

  var cnfrm = confirm("Are you sure!");
  if (cnfrm==true)
  {
    var data = {
      'action': 'aem_leave_event_process',
      'post_id': post_id
    };
    // We can also pass the url value separately from ajaxurl for front end AJAX implementations
    jQuery.post(ajax_object.ajax_url, data, function(response) {
        alert(response);
        location.reload();
    });
  }

});




jQuery("input").on("change", function() {
    this.setAttribute(
        "data-date",
        moment(this.value, "YYYY-MM-DD")
        .format( this.getAttribute("data-date-format") )
    )
}).trigger("change")


// Set up your table
table = jQuery('#points_table').DataTable({
  "pagingType": "full_numbers",
  dom: 'Bfrtip',
  buttons: [
    'copyHtml5',
    'excelHtml5',
    'csvHtml5',
    'pdfHtml5'
  ]
});

// Extend dataTables search
jQuery.fn.dataTable.ext.search.push(
  function(settings, data, dataIndex) {
    var min = jQuery('#min-date').val();
    var max = jQuery('#max-date').val();
    var createdAt = data[2] || 0; // Our date column in the table

    if (
      (min == "" || max == "") ||
      (moment(createdAt).isSameOrAfter(min) && moment(createdAt).isSameOrBefore(max))
    ) {
      return true;
    }
    return false;
  }
);

// Re-draw the table when the a date range filter changes
jQuery('.date-range-filter').change(function() {
  table.draw();
});

jQuery(document).on('click', '.list_of_category ul li a', function (e) {
  jQuery(".eventList").css("opacity",0.4);
  e.preventDefault();
    var term = jQuery(this).attr("term-id");
    jQuery(this).parent("li").addClass('current_item').siblings().removeClass('current_item');
    var data = {
      'action': 'aem_filter_post_by_category',
      'term': term
    };
    // We can also pass the url value separately from ajaxurl for front end AJAX implementations
    jQuery.post(ajax_object.ajax_url, data, function(response) {
        jQuery(".eventList").css("opacity",1);
        jQuery(".eventList").html(response);
        //location.reload();
    });

});


});