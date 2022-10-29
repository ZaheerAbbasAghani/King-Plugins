jQuery(document).ready(function(){

jQuery(".custom_measure").keypress(function(){
	jQuery("#aluminum_castte").prop( "checked", false );
});

    
  jQuery(".woosm .custom_measure").on('blur', function (e) {
        //jQuery(".single_add_to_cart_button").attr("disabled",true);
        var custom_height = jQuery("#_custom_height").val();
        var custom_width = jQuery("#_custom_width").val();
		if(jQuery("#_custom_height").val().length===0 && jQuery("#_custom_width").val().length===0) {
			alert("empty");
			jQuery("#ac").hide();
        	jQuery("#aluminum_castte").prop( "checked", false );
		}else{
			jQuery("#ac").show();
		}
        /*if(custom_width!=="" && custom_height!==""){
        	jQuery("#ac").show();
        }else{
        	jQuery("#ac").hide();
        	jQuery("#aluminum_castte").attr("checked", false);
        }*/


        var custom_price = 0;
        if(custom_width<24){
            alert("Width must be greater than 23");
            jQuery("#_custom_width").val("24");
            return false;
        }

        if(custom_height<48){
            alert("Height must be greater than 47");
            jQuery("#_custom_height").val("48");
            return false;
        }

if(custom_height >=48 && custom_height <=59){
    if(custom_width >=24 && custom_width <=35){
        custom_price = 80;
    }
    if(custom_width >=36 && custom_width <=47){
        custom_price = 89;
    }
    if(custom_width >=48 && custom_width <=59){
        custom_price = 111;
    }
    if(custom_width >=60 && custom_width<=71){
        custom_price = 135;
    }
    if(custom_width >=72 && custom_width<=83){
        custom_price = 161;
    }
    if(custom_width >=84 && custom_width<=95){
        custom_price = 182;
    }
    if(custom_width >=96 && custom_width<=107){
        custom_price = 198;
    }
    if(custom_width >=108 && custom_width<=119){
        custom_price = 245;
    }
    if(custom_width >=120 && custom_width<=131){
        custom_price = 282;
    }
    if(custom_width >=132 && custom_width<=143){
        custom_price = 324;
    }
    if(custom_width == 144){
        custom_price = 373;
    }
}

        //row 2
if(custom_height >=60 && custom_height <=71){
    if(custom_width >=24 && custom_width <=35){
        custom_price = 87;
    }
    if(custom_width >=36 && custom_width <=47){
        custom_price = 97;
    }
    if(custom_width >=48 && custom_width <=59){
        custom_price = 131;
    }
    if(custom_width >=60 && custom_width<=71){
        custom_price = 156;
    }
    if(custom_width >=72 && custom_width<=83){
        custom_price = 178;
    }
    if(custom_width >=84 && custom_width<=95){
        custom_price = 200;
    }
    if(custom_width >=96 && custom_width<=107){
        custom_price = 228;
    }
    if(custom_width >=108 && custom_width<=119){
        custom_price = 289;
    }
    if(custom_width >=120 && custom_width<=131){
        custom_price = 318;
    }
    if(custom_width >=132 && custom_width<=143){
        custom_price = 366;
    }
    if(custom_width == 144){
        custom_price = 420;
    }

}

//row 3
if(custom_height >=72 && custom_height <=83){
    //custom_price = SUCCESS";
    if(custom_width >=24 && custom_width <=35){
        custom_price = 96;
    }
    if(custom_width >=36 && custom_width <=47){
        custom_price = 107;
    }
    if(custom_width >=48 && custom_width <=59){
        custom_price = 142;
    }
    if(custom_width >=60 && custom_width<=71){
        custom_price = 178;
    }
    if(custom_width >=72 && custom_width<=83){
        custom_price = 200;
    }
    if(custom_width >=84 && custom_width<=95){
        custom_price = 231;
    }
    if(custom_width >=96 && custom_width<=107){
        custom_price = 259;
    }
    if(custom_width >=108 && custom_width<=119){
        custom_price = 325;
    }
    if(custom_width >=120 && custom_width<=131){
        custom_price = 358;
    }
    if(custom_width >=132 && custom_width<=143){
        custom_price = 411;
    }
    if(custom_width == 144){
        custom_price = 473;
    }

}

//row 4
if(custom_height >=84 && custom_height <=95){
    if(custom_width >=24 && custom_width <=35){
        custom_price = 110;
    }
    if(custom_width >=36 && custom_width <=47){
        custom_price = 122;
    }
    if(custom_width >=48 && custom_width <=59){
        custom_price = 156;
    }
    if(custom_width >=60 && custom_width<=71){
        custom_price = 195;
    }
    if(custom_width >=72 && custom_width<=83){
        custom_price = 226;
    }
    if(custom_width >=84 && custom_width<=95){
        custom_price = 260;
    }
    if(custom_width >=96 && custom_width<=107){
        custom_price = 298;
    }
    if(custom_width >=108 && custom_width<=119){
        custom_price = 363;
    }
    if(custom_width >=120 && custom_width<=131){
        custom_price = 399;
    }
    if(custom_width >=132 && custom_width<=143){
        custom_price = 459;
    }
    if(custom_width == 144){
        custom_price = 528;
    }

}

//row 5
if(custom_height >=96 && custom_height <=107){
    if(custom_width >=24 && custom_width <=35){
        custom_price = 125;
    }
    if(custom_width >=36 && custom_width <=47){
        custom_price = 139;
    }
    if(custom_width >=48 && custom_width <=59){
        custom_price = 175;
    }
    if(custom_width >=60 && custom_width<=71){
        custom_price = 218;
    }
    if(custom_width >=72 && custom_width<=83){
        custom_price = 254;
    }
    if(custom_width >=84 && custom_width<=95){
        custom_price = 290;
    }
    if(custom_width >=96 && custom_width<=107){
        custom_price = 328;
    }
    if(custom_width >=108 && custom_width<=119){
        custom_price = 398;
    }
    if(custom_width >=120 && custom_width<=131){
        custom_price = 438;
    }
    if(custom_width >=132 && custom_width<=143){
        custom_price = 503;
    }
    if(custom_width == 144){
        custom_price = 579;
    }

}

//row 6
if(custom_height >=108 && custom_height <=119){
    if(custom_width >=24 && custom_width <=35){
        custom_price = 136;
    }
    if(custom_width >=36 && custom_width <=47){
        custom_price = 151;
    }
    if(custom_width >=48 && custom_width <=59){
        custom_price = 195;
    }
    if(custom_width >=60 && custom_width<=71){
        custom_price = 235;
    }
    if(custom_width >=72 && custom_width<=83){
        custom_price = 282;
    }
    if(custom_width >=84 && custom_width<=95){
        custom_price = 323;
    }
    if(custom_width >=96 && custom_width<=107){
        custom_price = 363;
    }
    if(custom_width >=108 && custom_width<=119){
        custom_price = 424;
    }
    if(custom_width >=120 && custom_width<=131){
        custom_price = 466;
    }
    if(custom_width >=132 && custom_width<=143){
        custom_price = 536;
    }
    if(custom_width == 144){
        custom_price = 617;
    }

}

//row 7
if(custom_height >=120 && custom_height <=131){
    if(custom_width >=24 && custom_width <=35){
        custom_price = 151;
    }
    if(custom_width >=36 && custom_width <=47){
        custom_price = 168;
    }
    if(custom_width >=48 && custom_width <=59){
        custom_price = 216;
    }
    if(custom_width >=60 && custom_width<=71){
        custom_price = 261;
    }
    if(custom_width >=72 && custom_width<=83){
        custom_price = 313;
    }
    if(custom_width >=84 && custom_width<=95){
        custom_price = 359;
    }
    if(custom_width >=96 && custom_width<=107){
        custom_price = 403;
    }
    if(custom_width >=108 && custom_width<=119){
        custom_price = 471;
    }
    if(custom_width >=120 && custom_width<=131){
        custom_price = 518;
    }
    if(custom_width >=132 && custom_width<=143){
        custom_price = 595;
    }
    if(custom_width == 144){
        custom_price = 685;
    }

}
//row 8
if(custom_height >=132 && custom_height <=143){
    if(custom_width >=24 && custom_width <=35){
        custom_price = 186;
    }
    if(custom_width >=36 && custom_width <=47){
        custom_price = 207;
    }
    if(custom_width >=48 && custom_width <=59){
        custom_price = 267;
    }
    if(custom_width >=60 && custom_width<=71){
        custom_price = 321;
    }
    if(custom_width >=72 && custom_width<=83){
        custom_price = 386;
    }
    if(custom_width >=84 && custom_width<=95){
        custom_price = 442;
    }
    if(custom_width >=96 && custom_width<=107){
        custom_price = 496;
    }
    if(custom_width >=108 && custom_width<=119){
        custom_price = 580;
    }
    if(custom_width >=120 && custom_width<=131){
        custom_price =638;
    }
    if(custom_width >=132 && custom_width<=143){
        custom_price = 734;
    }
    if(custom_width == 144){
        custom_price = 844;
    }

}
//row 9
if(custom_height ==144){
    if(custom_width >=24 && custom_width <=35){
        custom_price = 186;
    }
    if(custom_width >=36 && custom_width <=47){
        custom_price = 207;
    }
    if(custom_width >=48 && custom_width <=59){
        custom_price = 267;
    }
    if(custom_width >=60 && custom_width<=71){
        custom_price = 321;
    }
    if(custom_width >=72 && custom_width<=83){
        custom_price = 386;
    }
    if(custom_width >=84 && custom_width<=95){
        custom_price = 442;
    }
    if(custom_width >=96 && custom_width<=107){
        custom_price = 496;
    }
    if(custom_width >=108 && custom_width<=119){
        custom_price = 580;
    }
    if(custom_width >=120 && custom_width<=131){
        custom_price = 638;
    }
    if(custom_width >=132 && custom_width<=143){
        custom_price = 734;
    }
    if(custom_width == 144){
        custom_price = 844;
    }

}

if(jQuery("#aluminum_castte").is(":checked")){

	if(custom_height >=60 && custom_height <=71){
		if(custom_width >=24 && custom_width <=35){
        	custom_price = 80 + 37;	
    	}else{
    		custom_price = 80;
    	}
	}
    		
}


/*jQuery('#aluminum_castte').toggle(
    function () { 
        jQuery('.check').attr('Checked','Checked'); 
    },
    function () { 
        $('.check').removeAttr('Checked'); 
    }
);*/

        //var sum = parseInt(inserted_height) + parseInt(inserted_width);
       // console.log(sum);    

        jQuery(".entry-summary .price").contents().filter(function() {
            return this.nodeType == 1;
        })
        .html("$"+custom_price);
     
    
  });



jQuery('#aluminum_castte').change(function() {
		var custom_height = jQuery("#_custom_height").val();
        var custom_width = jQuery("#_custom_width").val();

    	if (jQuery(this).is(":checked")) {
	        if(custom_width >=24 && custom_width <=35){
	        	var price = jQuery(".entry-summary .price span").text().replace("$", "");
	        	var plus = parseInt(price)+37;
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+plus);
	    	}
			if(custom_width >=36 && custom_width <=47){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	        	var plus = parseInt(price)+45;
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+plus);
			}
			if(custom_width >=48 && custom_width <=59){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	        	var plus = parseInt(price)+55;
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+plus);
			}
			if(custom_width >=60 && custom_width<=71){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	        	var plus = parseInt(price)+63;
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+plus);
			}
			if(custom_width >=72 && custom_width<=83){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	        	var plus = parseInt(price)+71;
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+plus);
			}
			if(custom_width >=84 && custom_width<=95){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	        	var plus = parseInt(price)+80;
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+plus);
			}
			if(custom_width >=96 && custom_width<=107){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	        	var plus = parseInt(price)+95;
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+plus);
			}
			if(custom_width >=108 && custom_width<=119){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	        	var plus = parseInt(price)+110;
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+plus);
			}
			if(custom_width >=120 && custom_width<=131){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	        	var plus = parseInt(price)+138;
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+plus);
			}
			if(custom_width >=132 && custom_width<=143){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	        	var plus = parseInt(price)+172;
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+plus);
			}
			if(custom_width == 144){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	        	var plus = parseInt(price)+215;
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+plus);
			}
	    }
	    else{
	    	if(custom_width >=24 && custom_width <=35){
		    	var price = jQuery(".entry-summary .price span").text().replace("$", "");
	    		var minus = parseInt(price)-37;	
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+minus);
	        }

        	if(custom_width >=36 && custom_width <=47){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	    		var minus = parseInt(price)-45;	
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+minus);
			}
			if(custom_width >=48 && custom_width <=59){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	    		var minus = parseInt(price)-55;	
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+minus);
			}
			if(custom_width >=60 && custom_width<=71){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	    		var minus = parseInt(price)-63;	
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+minus);
			}
			if(custom_width >=72 && custom_width<=83){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	    		var minus = parseInt(price)-71;	
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+minus);
			}
			if(custom_width >=84 && custom_width<=95){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	    		var minus = parseInt(price)-80;	
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+minus);
			}
			if(custom_width >=96 && custom_width<=107){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	    		var minus = parseInt(price)-95;	
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+minus);
			}
			if(custom_width >=108 && custom_width<=119){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	    		var minus = parseInt(price)-110;	
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+minus);
			}
			if(custom_width >=120 && custom_width<=131){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	    		var minus = parseInt(price)-138;	
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+minus);
			}
			if(custom_width >=132 && custom_width<=143){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	    		var minus = parseInt(price)-172;	
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+minus);
			}
			if(custom_width == 144){
				var price = jQuery(".entry-summary .price span").text().replace("$", "");
	    		var minus = parseInt(price)-215;	
	        	jQuery(".entry-summary .price").contents().filter(function() {
	            	return this.nodeType == 1;
	        	}).html("$"+minus);
			}


    	}
    
});






});

(function (jQuery) {

    jQuery(document).on('click', '.single_add_to_cart_button', function (e) {
        e.preventDefault();
            
            var custom_width = jQuery("#_custom_width").val();
            var custom_height = jQuery("#_custom_width").val();
            if(custom_width == ""){
                alert("Width field is empty");
                return false;
            }

            if(custom_width == ""){
                alert("Width field is empty");
                return false;
            }


    		var $thisbutton = jQuery(this),
            $form = $thisbutton.closest('form.cart'),
            id = $thisbutton.val(),
            product_qty = $form.find('input[name=quantity]').val() || 1,
            product_id = $form.find('input[name=product_id]').val() || id,
            custom_width = $form.find('#_custom_width').val(),
            custom_height = $form.find('#_custom_height').val(),
            variation_id = $form.find('input[name=variation_id]').val() || 0;
            price = jQuery(".entry-summary .price .woocommerce-Price-amount").text().replace("$", "");
	//alert(_custom_height);
        var data = {
            action: 'woocommerce_ajax_add_to_cart',
            product_id: product_id,
            product_sku: '',
            quantity: product_qty,
            variation_id: variation_id,
            custom_width:custom_width,
            custom_height:custom_height,
            price:price

        };

        jQuery(document.body).trigger('adding_to_cart', [$thisbutton, data]);

        jQuery.ajax({
            type: 'post',
            url: wc_add_to_cart_params.ajax_url,
            data: data,
            beforeSend: function (response) {
                $thisbutton.removeClass('added').addClass('loading');
            },
            complete: function (response) {
                $thisbutton.addClass('added').removeClass('loading');
            },
            success: function (response) {
                console.log(response);
                if (response.site_url) {
                    window.location = response.site_url;
                    return;
                } else {
                    jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                }
            },
        });
	
        return false;
    });
})(jQuery);