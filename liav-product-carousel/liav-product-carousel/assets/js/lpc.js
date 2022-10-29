jQuery(document).ready(function(){

        jQuery('.owl-carousel').owlCarousel({
            //center: true,
            items:3,
            loop:false,
            margin:10,
            nav:true,
            rtl:true,
		    responsive:{
		        0:{
		            items:1,
		        },
		        600:{
		            items:3
		        },
		        1000:{
		            items:3,
		        }
		    },
            navText: [jQuery('.jcarousel-control-next'),jQuery('.jcarousel-control-prev')]


        });


        /*jQuery('.owl-carousel').on('initialize.owl-carousel translate.owl.carousel', function(e){
            idx = e.item.index;
            jQuery('.owl-item.big').removeClass('big');
            //jQuery('.owl-item.medium').removeClass('medium');
            
            jQuery('.owl-item').eq(idx+1).addClass('big');
            jQuery('.owl-item').eq(idx+).addClass('big');
            if(jQuery(".owl-item").hasClass("big")){
                jQuery(this).find(".item").addClass("small_product");
            }
            
            
        });*/
    
        

});