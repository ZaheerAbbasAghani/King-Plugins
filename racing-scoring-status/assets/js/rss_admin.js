jQuery(document).ready(function(){

	jQuery(document).on("click",".rss_update_now", function(e){
		e.preventDefault();

		jQuery(this).attr("disabled",true);

		var data = {
			'action': 'rss_update_now',
			'status': 1
		};
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post(rss_ajax_object.ajax_url, data, function(response) {
			jQuery(".rss_update_now").attr("disabled",false);
			alert(response);
		});
	});

});