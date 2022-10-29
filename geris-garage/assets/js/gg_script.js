jQuery(document).ready(function(){

	jQuery(".gg_buy_now").click(function(e){
		
		if(!jQuery(this).hasClass("NotloginDone")){
			e.preventDefault();
			var id = jQuery(this).attr("data-id");
			jQuery(this).text("Loading...");

			var data = {
				'action': 'gg_buy_now_process',
				'id': id
			};
			jQuery.post(gg_ajax_object.ajax_url, data, function(response) {
				jQuery(".gg_buy_now").text("Buy Now");
				alert(response.message);
				window.location.replace(response.profile_url);
			});
		}

	});

	// DataTable
	jQuery('#gg_history_table').DataTable();

});