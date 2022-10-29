jQuery(document).ready(function(){
	jQuery(".bbaa a").click(function(e){
		e.preventDefault();
		var current_url = jQuery(location).attr("href");
		if (localStorage.getItem("site_url") === null) {
			var arr =[current_url]
			localStorage.setItem("site_url", JSON.stringify(arr));
		}else{
			var storedNames = JSON.parse(localStorage.getItem("site_url"));
			for (var i = 0; i < storedNames.length; i++) {
				var i = storedNames.length;
				storedNames[i] = current_url;
				localStorage.setItem("site_url", JSON.stringify(storedNames));
			}

		}

		var data = {
			'action': 'bbaa_login_process',
			'backUrl':  jQuery(location).attr("href")
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajax_object.ajax_url, data, function(response) {
			//alert(response);
			window.location.replace(response);
		});

	});

	if(jQuery("div").hasClass("bbaa")){
		jQuery("html").css("overflow-y","hidden !important");
	}

/*window.onbeforeunload = function() {
  localStorage.clear();
  return '';
};*/

	

});