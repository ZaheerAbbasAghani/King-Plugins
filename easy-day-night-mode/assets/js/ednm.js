jQuery(document).ready(function() {
	//Check if cookie exists
	$cook = document.cookie.indexOf('dark_mode');
	if($cook != -1){
		jQuery("body").addClass("dark-mode");
		jQuery("#dark-mode").html("<img src='"+myScript.pluginsUrl+"/assets/images/sun.png"+"' style='width:25px;cursor:pointer;'> ");
		//jQuery("body.dark-mode a").css("color","#fff");
	}

	// Cookie set 
	if(document.getElementById('dark-mode')){
		document.getElementById('dark-mode').addEventListener('click', function() {
		"use strict";
		//jQuery("body").addClass("dark-mode");
		if(document.querySelector("body.dark-mode")){
			//alert();
			document.cookie = "dark_mode=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
			console.log("Dark mode cookie deleted");
			location.reload(); 
		} else {
			var date = new Date();
			var daysToExpire = 30;
			date.setTime(date.getTime()+(daysToExpire*24*60*60*1000));
			document.cookie = "dark_mode=true; path=/; expires=" + date;
			location.reload(); 
		}

		});
	}

	// get all classes


	var classes = [];
	jQuery('div').each(function() {
		var firt_classes = jQuery(this)[0].classList[0];
		if(firt_classes!=undefined){
			classes.push(firt_classes);	
		}	    
	});

	document.cookie = "all_classes= "+ classes;
	//console.log(classes);


});
