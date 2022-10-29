jQuery(document).ready(function(){

	jQuery('#tks_date_picker').change(function(){
		if(jQuery(this).val() == ""){
			jQuery("#ppc-button").css("visibility","hidden");	
		}else{
        	jQuery("#ppc-button").css("visibility","visible");
		}
    });

});