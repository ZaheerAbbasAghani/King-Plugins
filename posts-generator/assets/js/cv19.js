jQuery(document).ready(function(){
	//console.log("ready");
	var cv19_url = "https://pomber.github.io/covid19/timeseries.json";
	
	// Display List of Deaths
	jQuery.getJSON( cv19_url, function( data ) {
		jQuery.each( data, function( key, val ) {
			jQuery(".cv19").append("<option value='"+key+"' class='cv19_country' name='"+key+"' >"+key+"</option>");
		});
	});

	// Display Statistics By Confirmed, Deaths, Recovered
	jQuery(".cv19").change(function() {
		var country = jQuery(this).val();
		jQuery.getJSON( cv19_url, function( data2 ) {
			jQuery.each( data2, function( key2, val2 ) {
				//console.log(val2)
				if(country == key2){
					var last = val2.pop();
			jQuery('#confirmed').animate({ 'opacity' : 0}, 400, function(){
				jQuery(this).html(last['confirmed']).animate({'opacity': 1}, 400);
			});

			jQuery('#deaths').animate({ 'opacity' : 0}, 400, function(){
				jQuery(this).html(last['deaths']).animate({'opacity': 1}, 400);
			});

			jQuery('#recovered').animate({ 'opacity' : 0}, 400, function(){
				jQuery(this).html(last['recovered']).animate({'opacity': 1}, 400);
			});
    


				}

				/*var confirmed =+ val2.pop();
				console.log(confirmed);*/
			});
		});
	});


});