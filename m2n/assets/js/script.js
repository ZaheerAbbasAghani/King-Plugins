jQuery(document).ready(function() {
 var url= "https://staging.bloemenvandegier.nl/wp-admin/edit.php?testtt=1"; 
 	jQuery(".wp-list-table .wc_actions a:first-child").before("<a href='"+url+"' class='pcustom' style='font-size: 13px;margin-top: 1px;border: 1px solid #ddd;padding: 5px 8px;border-color: #0071a1;border-radius: 4px;text-align: center;display: block;float: left;' target='_blank'> P </a>");


 	jQuery(".post-type-shop_order .wp-list-table tr").each(function(){

 			var id = jQuery(this).attr("id");
 			jQuery("#"+id).find(".pcustom").attr("href","?spost_id="+id);
 			//console.log(id);
 	});

	jQuery(document).ready(function() {
	    jQuery('.post-type-shop_order .wp-list-table').DataTable( {
        	"order": [[ 4, "desc" ]]
    	});
	});

 	
 	//Print
 	/*jQuery('body').on('click', 'a.pcustom', function(e) {
 		e.preventDefault();

 		var data = {
			'action': 'my_pdf_custom_field',
			'pdf': 1
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajax_object.ajax_url, data, function(response) {
				//console.log(response);
			 window.open(response, '_blank');
		});


 	});*/

});