jQuery(document).ready(function() {
// Table for individual users
jQuery('#student_reports').DataTable({
    "pagingType": "full_numbers",
    dom: 'Bfrtip',
    buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5'
    ]
});

// Accordion for courses
jQuery( "#lmswr_accordion" ).accordion({
    collapsible: true
});


// DataTable for courses inside accordion
var len = jQuery("#lmswr_accordion .ui-accordion-content").length;
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






});