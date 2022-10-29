jQuery(document).ready(function(){
	var names = [];
	jQuery("#order_line_items tr.item").each(function(){
		var name = jQuery(this).find(".name").attr("data-sort-value");
		names.push(name);
	});

	var data = {
		'action': 'wfu_show_images_list',
		'names': names,
		'order_id': jQuery("#post_ID").val()
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(wfu_object.ajaxurl, data, function(response) {
		console.log(response);
	});

});