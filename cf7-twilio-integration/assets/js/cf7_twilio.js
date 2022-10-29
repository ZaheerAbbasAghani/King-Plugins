jQuery(document).ready(function(){
	if(jQuery("#allow_visitors").prop("checked") == true){
			jQuery(".fieldsetwrap").fadeIn();
	}
	jQuery("#allow_visitors").change(function(){
		if(this.checked) {
        	jQuery(".fieldsetwrap").fadeIn();
        	jQuery("#permission").val("Yes");
    	}else{
    		jQuery(".fieldsetwrap").hide();
    		jQuery("#permission").val("No");
    	}
	});


	jQuery("#wpcf7-admin-form-element #informationdiv").after("<div class='postbox'> <h3> Help with Twilio SMS </h3><div class='inside'><p>Here are some links to help you set up Twilio SMS</p><ol><li><a href='https://www.twilio.com' target='_blank'>Twilio</a></li><li><a href='#' target='_blank'>How to use</a></li><li><a href='#' target='_blank'>FAQ</a></li></ol></a></div>");
	
	// Removing contact form 7 tags
	jQuery("#cf7si-sms-sortables fieldset legend span").removeClass("used");
	jQuery("#cf7si-sms-sortables fieldset legend span").removeClass("unused");


	
	// Selecting Tags FROM Twilio Admin Tags List
	var tags = [];
	jQuery(".box1 fieldset legend span").each(function(){
		var span = jQuery(this).text();
		tags.push(span)
	});

	var txtarea = jQuery('.box1 .form-table #wpcf7-mail-body').html();
	var result = txtarea.match(/\[(.*)\]/g);
	// Replace Tags
	var diff = jQuery(tags).not(result).get();
	jQuery(".box1 fieldset legend span").each(function(){
		var spann = jQuery(this).text();
		jQuery(diff).each(function(index,value){
			if(value == spann){
				jQuery(".box1 fieldset legend span:contains('"+value+"')").addClass("cf7_items");			
			}
		});
	});


	// Selecting Tags FROM Twilio Admin Users List
	var tagss = [];
	jQuery(".fieldsetwrap fieldset legend span").each(function(){
		var spannn = jQuery(this).text();
		tagss.push(spannn)
	});
	
	var txtareaa = jQuery('.fieldsetwrap .form-table #wpcf7-mail-body').html();
	var resultt = txtareaa.match(/\[(.*)\]/g);
	
	// Replace Tags
	var difff = jQuery(tagss).not(resultt).get();
	jQuery(".fieldsetwrap fieldset legend span").each(function(){
		var spannnn = jQuery(this).text();
		jQuery(difff).each(function(index,valuee){
			if(valuee == spannnn){
				jQuery(".fieldsetwrap fieldset legend span:contains('"+valuee+"')").addClass("cf7_items");			
			}
		});
	});

	

});