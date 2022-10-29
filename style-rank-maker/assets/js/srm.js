jQuery(document).ready(function(){

	jQuery(".slick-slide img").click(function(){
		if(!jQuery(this).parent().hasClass("srm_active")){
			jQuery(this).parent().addClass("srm_active");

			var id = jQuery(this).attr("data-id");
			var score = jQuery(".srm_active").length;
			var oldData = localStorage.getItem("temp_ranking");

		    if(oldData !== null) {
		    	if(score == 1){
		        	localStorage.setItem("temp_ranking", oldData+id +"_"+3+"_");
		        }
		        if(score == 2){
		        	localStorage.setItem("temp_ranking", oldData+id +"_"+2+"_");
		        }
		        if(score == 3){
		        	localStorage.setItem("temp_ranking", oldData+id +"_"+1+"_");
		        }
		    } else {
		        //localStorage.setItem("ranking", id);

		        if(score == 1){
		        	localStorage.setItem("temp_ranking", id+"_"+3+"_");
		        }
		        if(score == 2){
		        	localStorage.setItem("temp_ranking", id+"_"+2+"_");
		        }
		        if(score == 3){
		        	localStorage.setItem("temp_ranking", id+"_"+1+"_");
		        }

		    }
		}

	});

	// Show Slider
	jQuery(".srm_slider").slick({

	  // normal options...
	  	infinite: false,
	    slidesToShow: 3,
	    slidesToScroll: 3,
	    draggable:false,
	    fnCanGoNext: function(instance, currentSlide){

         
        var oldData = localStorage.getItem("temp_ranking");
        var ranking = localStorage.getItem("ranking");
		if(ranking !== null) {
        	localStorage.setItem("ranking", ranking+oldData);
        }else{
        	localStorage.setItem("ranking", oldData);
        }

        localStorage.removeItem("temp_ranking");

       var currentSlide = instance.$slides.eq(currentSlide).attr("class").split(' ')[1];
       	var active = jQuery("."+currentSlide).length;
       //	console.log(jQuery(".srm_active").length);
       	if(jQuery("."+currentSlide).hasClass("srm_active")){
       		if(jQuery(".srm_active").length == active){
       			jQuery(".slick-slide").removeClass("srm_active")
       			return true; 

       		}else{
       			return false; 
       		}
      	}

            
        },
	  	// the magic
	  	responsive: [{

	      breakpoint: 1024,
	      settings: {
	        slidesToShow: 3,
	        infinite: true
	      }

	    }, {

	      breakpoint: 600,
	      settings: {
	        slidesToShow: 2,
	        dots: true
	      }

	    }, {

	      breakpoint: 300,
	      settings: "unslick" // destroys slick

	    }]
	});


	jQuery(".srm_onboarding .btn-warning").click(function(e){
		e.preventDefault();
		jQuery(".srm_onboarding").remove();
	});


	jQuery(".srm_slider").on('afterChange', function(event, slick, currentSlide) {
	  	if (slick.currentSlide >= slick.slideCount - slick.options.slidesToShow) {
	        jQuery(".srm_slider .slick-next").remove();
			setTimeout(function(){
			  jQuery(".srm_slider").append("<a href='#' class='view_result'> View Result</a>");
			}, 3000);

	        
	    }
	});
	jQuery('body').on('click', '.view_result', function(e) {
		e.preventDefault();
	
		var d = localStorage.getItem("ranking");
		var data = {
			'action': 'srm_save_rankings',
			'dataType': 'json',
			'ranking_details': d
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(srm_object.ajax_url, data, function(response) {
			
			if(response.log == 1){
				jQuery(".srm_slider").html(response.rhtml);
				jQuery(".srmUndo").remove();
				localStorage.removeItem("temp_ranking");
				localStorage.removeItem("ranking");
				var origin   = window.location.origin;
				setTimeout(function(){
				   window.location.replace(origin+'/'+response.style);
				}, 3000);
			}else{
				alert(response.message);
				jQuery(".srmUndo").remove();
				var origin = window.location.origin;
				localStorage.removeItem("temp_ranking");
				window.location.replace(origin+'/login');
			}


		});
	});

	// Undo Selection
	jQuery('body').on('click', '.srmUndo', function(e) {
		e.preventDefault();
		if(jQuery(".slick-slide").hasClass("srm_active")){
			localStorage.removeItem("temp_ranking");
			jQuery(".slick-slide").removeClass("srm_active");
		}
	});

});