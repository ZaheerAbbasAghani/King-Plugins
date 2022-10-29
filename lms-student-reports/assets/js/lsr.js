var minDate, maxDate;
 
// Custom filtering function which will search data in column four between two values
jQuery.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var min = minDate.val();
        var max = maxDate.val();
        var date = new Date( data[3] );
 
        if( ( min === null && max === null ) || ( min === null && date <= max ) ||
            ( min <= date   && max === null ) || ( min <= date   && date <= max )) 
        {
            return true;
        }
        return false;
    }
);


jQuery(document).ready(function() {
        
     // Create date inputs
    minDate = new DateTime(jQuery('#min'), {
        format: 'MMMM Do YYYY'
    });
    maxDate = new DateTime(jQuery('#max'), {
         format: 'MMMM Do YYYY'
    });
 
    // DataTables initialisation
    var table = jQuery('#example').DataTable({
            "pagingType": "full_numbers",
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
    });
 
    // Refilter the table
    jQuery('#min, #max').on('change', function () {
        table.draw();
    });

});