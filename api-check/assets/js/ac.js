jQuery(document).ready(function(){


	jQuery(document).on("blur","#billing_postcode", function(){
		
		var country = jQuery("#billing_country option:selected").val();
			var address = jQuery("#house_no").val();
			var postcode = jQuery("#billing_postcode").val();

		if(country != "" && address != "" && postcode != ""){


			
			jQuery("#billing_address_1").css("opacity","0.5");
			jQuery("#billing_address_1").addClass('loadinggif');


			var data = {
				'action': 'ac_search_address',
				'country': country,
				'address':address,
				'postcode':postcode,

			};
			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			jQuery.post(ac_ajax_object.ajax_url, data, function(response) {

				if(response.status == 1){
					var street = response.result.data.street;
					var city = response.result.data.city;

					jQuery("#billing_address_1").val(street+" "+city);
					jQuery("#billing_address_1").css("opacity",1);
					jQuery("#billing_address_1").removeClass('loadinggif');
				}else{
					alert(response.result);
					jQuery("#billing_address_1").css("opacity",1);
					jQuery("#billing_address_1").removeClass('loadinggif');
				}


			});
		}
	});


	jQuery(document).on("blur","#house_no", function(){
		
		var country = jQuery("#billing_country option:selected").val();
			var address = jQuery("#house_no").val();
			var postcode = jQuery("#billing_postcode").val();

		if(country != "" && address != "" && postcode != ""){

			

			jQuery("#billing_address_1").css("opacity","0.5");
			jQuery("#billing_address_1").addClass('loadinggif');


			var data = {
				'action': 'ac_search_address',
				'country': country,
				'address':address,
				'postcode':postcode,

			};
			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			jQuery.post(ac_ajax_object.ajax_url, data, function(response) {

				if(response.status == 1){
					
					//var number = response.result.data.number;
					var street = response.result.data.street;
					var city = response.result.data.city;

					jQuery("#billing_address_1").val(street+" "+city);
					jQuery("#billing_address_1").css("opacity",1);
					jQuery("#billing_address_1").removeClass('loadinggif');

				}else{
					alert(response.result);
					jQuery("#billing_address_1").css("opacity",1);
					jQuery("#billing_address_1").removeClass('loadinggif');
				}


			});
		}
	});

	var html = jQuery(".houseNo").html();
	jQuery("#billing_country_field").append(html);


	var html = jQuery("#billing_postcode_field").html();
	jQuery("#billing_country_field").append(html);


	jQuery("#billing_country").on("change", function(){
		//alert("HELLO WORLD");
		jQuery("#house_no").val("");
		jQuery("#billing_postcode").val("");
	});


});