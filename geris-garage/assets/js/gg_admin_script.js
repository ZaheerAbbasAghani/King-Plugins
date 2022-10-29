jQuery(document).ready(function(){

	jQuery( "#accordion" ).accordion({
  		collapsible: true, active: true, heightStyle: "content"
  	});

	// DataTable
	jQuery("#accordion .ui-accordion-content").each(function(key,value){
		jQuery(this).find('#gg_history_table_'+key).DataTable();
	});

	// Reset

	jQuery('body').on('click', 'a.gg_reset_now', function(e) {
		e.preventDefault();
		 if(confirm("Are you sure you want to reset this?")){

			jQuery(this).text("Reseting...");
			var tbl = jQuery(this).parent().find("table").attr("id");
			

			var product_ids = [];
			jQuery("#"+tbl+" tbody").find("tr").each(function(){
				if(jQuery(this).attr("data-status") == 0){
					var id = jQuery(this).attr("data-id");
					product_ids.push(id);

				}
				
			});

			//console.log(product_ids);
			var data = {
				'action': 'gg_reset_now_process',
				'product_ids': product_ids
			};
			jQuery.post(gg_ajax_object.ajax_url, data, function(response) {
				jQuery("a.gg_reset_now").text("Reset");
				alert(response);
				location.reload();
			});
		}else{
			return false;
		}
	});




});