jQuery(document).ready(function(){
  //Start Date
  jQuery('#gstl_start_date_time').datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function(datetext) {
            var d = new Date(); // for now

            var h = d.getHours();
            h = (h < 10) ? ("0" + h) : h ;

            var m = d.getMinutes();
            m = (m < 10) ? ("0" + m) : m ;

            var s = d.getSeconds();
            s = (s < 10) ? ("0" + s) : s ;

            datetext = datetext + " " + h + ":" + m + ":" + s;

            jQuery('#gstl_start_date_time').val(datetext);
            
        }
    });

  //End Date
  jQuery('#gstl_end_date_time').datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function(datetext) {
            var d = new Date(); // for now

            var h = d.getHours();
            h = (h < 10) ? ("0" + h) : h ;

            var m = d.getMinutes();
            m = (m < 10) ? ("0" + m) : m ;

            var s = d.getSeconds();
            s = (s < 10) ? ("0" + s) : s ;

            datetext = datetext + " " + h + ":" + m + ":" + s;

            jQuery('#gstl_end_date_time').val(datetext);
        }
    });

    // Add members in list

    jQuery(".gstl_member").click(function(e){
        e.preventDefault();

        if(confirm("Are you sure?")){

            var id = jQuery(this).attr("member-id");
            var post_id = jQuery(this).attr("post-id");
            var limit = jQuery(this).parent().find(".member_limit").val();
            var data = {
                'action': 'gstl_add_dj_in_list',
                'post_id':post_id,
                'user_id':id,
                'limit':limit
            };
            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            jQuery.post(gstl_object.ajax_url, data, function(response) {
                alert(response);
                location.reload();
            });
        }
        else{
            return false;
        }
    });

     // Add members in list

    jQuery(".gstl_member_delete").click(function(e){
        e.preventDefault();

        if(confirm("Are you sure?")){

            var id = jQuery(this).attr("member-id");
            var post_id = jQuery(this).attr("post-id");
            var limit = jQuery(this).attr("limit");
            var data = {
                'action': 'gstl_remove_dj',
                'post_id':post_id,
                'user_id':id,
                'limit':limit
            };
            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            jQuery.post(gstl_object.ajax_url, data, function(response) {
                alert(response);
                location.reload();
            });
        }
        else{
            return false;
        }
    });

});