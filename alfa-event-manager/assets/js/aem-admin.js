jQuery(document).ready(function(){
jQuery( "#aem_accordion" ).accordion({
    collapsible: true
});
var len = jQuery("#aem_accordion .ui-accordion-content").length;
for (var i = 1; i<=len; i++) {
    jQuery('#student_reports_'+i).DataTable({
    "pagingType": "full_numbers",
    dom: 'Bfrtip',
    buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5'
    ]

    });
}

jQuery('#points_by_role_reports').DataTable({
    "pagingType": "full_numbers",
    dom: 'Bfrtip',
    buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5'
    ]
});

jQuery('#points_by_user_reports').DataTable({
    "pagingType": "full_numbers",
    dom: 'Bfrtip',
    buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5'
    ]
});


jQuery("input").on("change", function() {
    this.setAttribute(
        "data-date",
        moment(this.value, "YYYY-MM-DD")
        .format( this.getAttribute("data-date-format") )
    )
}).trigger("change")


// Set up your table
table = jQuery('#points_by_user_reports2').DataTable({
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
    var createdAt = data[3] || 0; // Our date column in the table

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


jQuery(".assign_points").click(function(e){
    e.preventDefault();
    jQuery(".points_box").fadeIn();
   
    var points = jQuery(this).data("points");
    var user_id = jQuery(this).attr("user-id");
    var event_id = jQuery(this).attr("event-id");
    var user_role = jQuery(this).attr("user_role");

    jQuery("#total_points").val(points);
    jQuery("#user_id").val(user_id);
    jQuery("#event_id").val(event_id);
    jQuery("#user_role").val(user_role);
   
}); //end click function

jQuery(".cancelbtn").click(function(){
    jQuery(".points_box").fadeOut();
});


jQuery(".give_points").click(function(e){
    e.preventDefault();

    if(jQuery("#assign_points").val().length === 0 ){
        alert("Assign Point field is empty.");
        return false;
    }

    var user_id = jQuery("#user_id").val();
    var event_id = jQuery("#event_id").val();
    var total_points = jQuery("#total_points").val();
    var obtained_points = jQuery("#assign_points").val();
    var user_role = jQuery("#user_role").val();

    if(user_role == "um_fux"){
    	if(parseInt(obtained_points) > parseInt(total_points)){
            alert("Total Point Limit is "+total_points);
        	return false;	
    	}
    }

    if(user_role == "um_bursch"){
    	if(parseInt(obtained_points) > parseInt(total_points)){
    		alert("Total Point Limit is "+total_points);
        	return false;	
    	}
    }

    if(user_role == "um_alter-herr"){
    	if(parseInt(obtained_points) > parseInt(total_points)){
    		alert("Total Point Limit is "+total_points);
        	return false;	
    	}
    }

    var data = {
        'action': 'create_point_for_user',
        'user_id': user_id,
        'event_id': event_id,
        'total_points': total_points,
        'obtained_points': obtained_points
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajaxurl, data, function(response) {
        alert(response);
        location.reload();
    });

}); //end click function

jQuery("#speaker_id option").click(function (e) {

    var all = jQuery("#speaker_id :selected").map(function () {
        return this.value;

    }).get();  // all selected value

    if (all.indexOf(this.value) != -1) {  // check the condition your selecting or unselected  option
        var roleVal = this.value;  // current selected element
        jQuery("."+roleVal).fadeIn("slow");
    }else{
        var roleVal = this.value;  // current selected element
        jQuery("."+roleVal).fadeOut("slow");
    }

});


jQuery("#speaker_id option:selected").each(function(){
    var val = jQuery(this).val();
    jQuery("."+val).fadeIn("slow");
})

//jQuery("select[name^='post_author']").val(selected).attr('selected','selected');


});