jQuery(document).ready(function(){

setTimeout(function() {
	var i=0;
    jQuery(".variationsss").each(function(){
		var variation = jQuery(this).text();
		//console.log(variation);
		var select_var = jQuery(".origina_variations option[value='"+variation+"']").val();
		if (select_var == variation ){
  			jQuery(".origina_variations option[value='"+variation+"']").attr('class','sevven'); 	

		}else{
			jQuery(".origina_variations option").removeClass('enabled'); 
			jQuery(".origina_variations option").removeClass('attached'); 
			jQuery(".origina_variations option").val("");
		}

		i++;
	});

}, 3000);


jQuery( ".variations_form" ).on( "woocommerce_variation_has_changed", function () {
	var i=0;
    jQuery(".variationsss").each(function(){
		var variation = jQuery(this).text();
		var select_var = jQuery(".origina_variations option[value='"+variation+"']").val();
		if (select_var == variation ){
  			jQuery(".origina_variations option[value='"+variation+"']").attr('class','sevven'); 	
			
		}else{
			
			jQuery(".origina_variations option").removeClass('enabled'); 
			jQuery(".origina_variations option").removeClass('attached'); 
			jQuery(".origina_variations option").val("");
			
		}
		i++;
	});


	jQuery(".origina_variations option").each(function(){
		if(jQuery(this).hasClass("enabled")){
				jQuery(this).removeClass('enabled'); 
				jQuery(this).removeClass('attached'); 
				jQuery(this).val("");
				jQuery(this).addClass("consultation");
		}
	});




   
});

setTimeout(function() {
	jQuery(".origina_variations option").each(function(){
		if(jQuery(this).hasClass("enabled")){
				jQuery(this).removeClass('enabled'); 
				jQuery(this).removeClass('attached'); 
				jQuery(this).val("");
				jQuery(this).addClass("consultation");
		}
	});
}, 4000);


	jQuery('.origina_variations').change(function(){ 
	    var value = jQuery(this).val();
	    if(value==""){
			jQuery(".mybtn").hide();
			jQuery(".woocommerce-variation-add-to-cart").hide();
    		jQuery(".variations_form").append('<a href="https://pharmacy.pa9e.com/excessive-sweating-consultation-form/" class="mybtn">Request a Consult </a>');	    	
	    }else{
	    	jQuery(".woocommerce-variation-add-to-cart").show();
    		jQuery(".mybtn").hide();
	    }
	});

});