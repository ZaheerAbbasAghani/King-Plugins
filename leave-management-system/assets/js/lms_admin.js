jQuery(document).ready(function() {
    jQuery('#lms_list').DataTable();

    //jQuery(".leaveStatus").on("change", function(e){
    jQuery(document).on("change",".leaveStatus", function(e){
        e.preventDefault();

        if(confirm("Are you sure?")){
        
            var values = jQuery(this).serialize();
            var data = {
                'action': 'lms_update_leave_status',
                'id': jQuery(this).attr("data-id"),
                'status':jQuery(this).find("option:selected").val(),
                'user_name': jQuery(this).parent().parent().find("td").attr("data-name"),
                'user_email': jQuery(this).parent().parent().find(".user_email").text()
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(lms_ajax_object.ajax_url, data, function(response) {
                alert(response);
                location.reload();
            });

        }else{
            return false;
        }

    });

    jQuery('#tabs').tabs();


    jQuery(document).on("click",".dltbtn", function(e){
        e.preventDefault();

        if(confirm("Are you sure?")){
        
            var values = jQuery(this).serialize();
            var data = {
                'action':'lms_delete_leave_request',
                'id': jQuery(this).attr("data-id"),
            }

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(lms_ajax_object.ajax_url, data, function(response) {
                alert(response);
                location.reload();
            });

        }else{
            return false;
        }

    });

});