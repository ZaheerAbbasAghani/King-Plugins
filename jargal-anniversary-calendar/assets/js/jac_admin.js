jQuery(document).ready(function() {
    // Table for subscribers
    jQuery('#subscriber_reports').DataTable({
        "pagingType": "full_numbers",
        dom: 'Bfrtip',
        buttons: [

            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });


    jQuery(".delete_it").click(function(e){
        e.preventDefault();

        var id = jQuery(this).attr("data-id");

        var data = {
            'action': 'jac_subscriber_remove',
            'id': id,
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajax_object.ajax_url, data, function(response) {
            alert(response);
            location.reload();
        });


        //alert(id);

    });


    jQuery(".send_email").click(function(e){
        e.preventDefault();

        var id = jQuery(this).attr("post_id");

        var data = {
            'action': 'jac_send_test_email',
            'id': id,
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajax_object.ajax_url, data, function(response) {
            alert(response);
            //location.reload();
        });


    });

});