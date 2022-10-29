jQuery(document).ready(function(){

	jQuery("#post-wrapper .post-column:first-child article.sticky").before("<h3>Anniversaire(s) du jour</h3>");

	jQuery("#jac_subscribe").on('submit', function(event) {
		event.preventDefault();
		//alert("W");
		var email = jQuery("#subscribe_it").val();
		var post_id = jQuery("input[type='radio'].subscribe:checked").val();
		//alert(post_id);
		var data = {
			'action': 'jac_subscribe_now',
			'email': email,
			'post_id': post_id,
			
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajax_object.ajax_url, data, function(response) {
			alert(response);
			location.reload();
		});
		
	});

});