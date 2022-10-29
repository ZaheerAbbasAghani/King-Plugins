jQuery(document).ready(function(){

	if ("total" in localStorage) {
		localStorage.removeItem("total");
	}


	jQuery(".wep_user_response").simpleform({
		speed : 500,
		transition : 'fade',
		progressBar : true,
		showProgressText : true,
		validate: true
	});


	jQuery(document).on("click", "#next-fieldset", function(){

		if(jQuery(".wep_user_response ul.list fieldset.personal-data").eq(0).find("input[type='radio']:checked").val() == "multiple" && jQuery(".second_person").length == 0){
			jQuery(".wep_user_response ul.list fieldset.personal-data").eq(1).append("<input type='text' name='multiple' placeholder='Would you like to share their first name?' class='second_person'> ");
		}


		if(jQuery(".wep_user_response ul.list fieldset.personal-data").eq(0).find("input[type='radio']:checked").val() == "single"){
			jQuery(".second_person").remove();

			jQuery(".personal-data").each(function(index,value){
				var p = jQuery(this).find(".paragraph").parent().attr("class");
				if(typeof  p != 'undefined'){
					
					if (localStorage.getItem("total") === null) {

						var total = jQuery(".list .personal-data").length;
						var ct = parseInt(p.split(' ')[1] - 1);
						var t = parseInt(total) - ct;

						jQuery('fieldset.personal-data:gt('+t+')').remove();
						localStorage.setItem("total", total);
					}
				}
				
			});

			var last = jQuery(".list fieldset.personal-data:last-child").attr("class");
			var last_slide =  last.split(' ')[1];

			var current = jQuery(".list fieldset.personal-data:not(:hidden)").attr("class").split(' ')[1];

			if(parseInt(current) + parseInt(1) == last_slide){
				jQuery("#next-fieldset").remove();

				setTimeout(function(){
				   jQuery("#submit-button").show();
				   jQuery(".paragraph").text("Please submit the form to see the results.");
				   
				   jQuery(".wep_user_response .progress-bar .progress-bg").attr("style", "width:100%");

				}, 1000);
			}

			var len = jQuery("ul.list fieldset").length;
			var pos = jQuery(".list fieldset.personal-data:not(:hidden)").index() + parseInt(2);

			jQuery(".wep_user_response .progress-bar span.progress-text").html((pos+ "/"+ len));
		}

		jQuery(this).addClass("newLables");

	});

	jQuery(document).on("click", ".newLables", function(){

		jQuery(".wep_user_response fieldset").each(function(k,v){

				var fname = jQuery(".wep_user_response ul.list fieldset.personal-data").eq(1).find("input[type='text']").eq(0).val();
		
				var sname = jQuery(".wep_user_response ul.list fieldset.personal-data").eq(1).find("input[type='text']").eq(1).val();

				jQuery(".Breakpoint").val(fname+'_'+sname);

				var oldQ = jQuery(this).find("label").eq(0).html().replace('[fname]',"<b>"+fname+"</b>").replace('[sname]',"<b>"+sname+"</b>");

				jQuery(this).find("label").eq(0).html(oldQ);

				jQuery("#next-fieldset").removeClass(".newLables");

		});

	});


	jQuery(".wep_user_response fieldset").each(function(k,v){
		//jQuery(this).addClass(k);
		jQuery(this).addClass("k_"+k);

	});



	jQuery("#wep_user_response").submit(function(e){

		e.preventDefault();

		jQuery(this).find("#submit-button").attr("disabled", true);

		var data = {
			'action': 'wep_submit_form',
			'formData': jQuery(this).serialize()
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(gf_ajax_object.ajax_url, data, function(response) {
			//console.log(response);
			jQuery(".gf_response").html(response);

			jQuery(".wep_user_response").fadeOut();

			jQuery(".wep_user_response").before("<a href='' class='button'> Try Again? </a>");


			jQuery("#owl-example-1").owlCarousel({
			  	autoWidth: false,
			  	loop: false,
			  	margin: 15,
			  	nav: true,
			  	responsive:{
			        0:{
			            items:1
			        },
			        600:{
			            items:2
			        },
			        1000:{
			            items:3
			        }
		    	},
			});

			jQuery("#owl-example-2").owlCarousel({
			  	autoWidth: false,
			  	loop: false,
			  	margin: 15,
			  	nav: true,
			  	responsive:{
			        0:{
			            items:1
			        },
			        600:{
			            items:2
			        },
			        1000:{
			            items:3
			        }
		    	},
			});


			jQuery("#submit-button").attr("disabled", false);

			//console.log(response);
			localStorage.removeItem("total");

		});
	});

	jQuery(".connect-block-element-5L4et .et_b_header-search").before("<a href='https://www.geekpassionsgifts.com/gift-finder' class='button-gift-finder'> Gift Finder </a>");


	jQuery(".header-mobile-menu ul.menu").append("<li class='btn2'> <a href='https://www.geekpassionsgifts.com/gift-finder' class='button-gift-finder-2'> Gift Finder </a> </li>");


	jQuery(".mobile-header-wrapper .header-main").append("<a href='https://www.geekpassionsgifts.com/gift-finder' class='button-gift-finder-2'> Gift Finder </a>");
	


});