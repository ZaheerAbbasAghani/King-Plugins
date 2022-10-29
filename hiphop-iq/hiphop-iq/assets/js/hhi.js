jQuery(document).ready(function(){
	//FAQ FORM

	if ("giftDone" in localStorage) {

			//FAQ Banner 
		var banner_title = ajax_object.banner_title;
		var banner_sub_title = ajax_object.banner_sub_title;
		var banner_button = ajax_object.banner_button;
		var banner_image = ajax_object.banner_image;

		jQuery(".wintshirt").attr("src",banner_image);	

		jQuery("#fs-form-wrap").append("<div class='faqMainBanner' style='margin-top:10%;'><h1> Thanks for participating </h1><p>You can try again after 1 month.</p>");

		var Lastclear = localStorage.getItem("Lastclear"),
            Time_now  = (new Date()).getTime();
        if ((Time_now - Lastclear) > 1000 * 60 * 60 * 24 * 30) {
          localStorage.removeItem("giftDone");
          localStorage.setItem("Lastclear", Time_now);
        }



	}else{

		var formWrap = document.getElementById( 'fs-form-wrap' );
		new FForm( formWrap, {
			onReview : function() {
				classie.add( document.body, 'overview' ); 
			},
			
		} );

		//FAQ Banner 
		var banner_title = ajax_object.banner_title;
		var banner_sub_title = ajax_object.banner_sub_title;
		var banner_button = ajax_object.banner_button;
		var banner_image = ajax_object.banner_image;

		jQuery(".wintshirt").attr("src",banner_image);	

		jQuery("#fs-form-wrap").append("<div class='faqMainBanner'><h1>"+banner_title+"</h1><img src='"+banner_image+"' style='width:400px;margin-bottom:30px;    border-radius: 4px;'><p>"+banner_sub_title+"</p><button class='button primary'>"+banner_button+"</button></div>");

		

	}

	// FAQ HIDE BANNER
	jQuery('body').on('click', '.faqMainBanner .primary', function() {
    		jQuery(".faqMainBanner").fadeOut();
	});

	// Store Form Values
	jQuery("#myform").submit(function(e) {
		e.preventDefault(); // avoid to execute the actual submit of the form.
		jQuery(".fs-form-wrap").css("opacity","0.3");
		var myform = jQuery(this).serializeArray();
		var data = {
			'action': 'jf_store_and_response',
			'faq_info': myform
		};
		jQuery.post(ajax_object.ajax_url, data, function(response) {
			if(response.status == "already"){
				alert(response.message);
				jQuery(".fs-form-wrap").css("opacity","1");
			}else{
				//console.log(response);
				jQuery(".fs-form-wrap").html(response);
				jQuery(".fs-form-wrap").css("opacity","1");
			}
			
		});

	});




});