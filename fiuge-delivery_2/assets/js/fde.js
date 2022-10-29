jQuery(document).ready(function(){

	jQuery(".delivery_option").on("change", function(){

	var val  = jQuery(this).val();
		if(val == "Pickup"){
			//val = "Pickup";	

			jQuery(".fde_delivery").remove();
			var subtotal=jQuery('.cart-subtotal .woocommerce-Price-amount bdi').text();
			var price = jQuery('.order-total bdi').text(subtotal);



		}else if ("Fiuge Delivery") {
			val = "Fiuge Delivery";

			jQuery("form.woocommerce-checkout").css("opacity","0.4");
			jQuery("form.woocommerce-checkout").attr("disabled",true);

			var country_code = jQuery(".selected-dial-code").text();

			var address = jQuery("#billing_address_1").val();
			var country = jQuery("#billing_country option:selected").val();
			var zipcode = jQuery("#billing_postcode").val();

			if(address == "" && country == "" && zipcode == ""){
				alert("Please fill address, country, zipcode fields");
				return false;
			}

			var data = {
				'action': 'fde_search_for_shipping_price',
				'address': address,
				'country':country,
				'zipcode':zipcode,
				'country_code':country_code,
				'dataType':'json',
			};


			jQuery.post(fde_ajax_object.ajax_url, data, function(response) {

			
				jQuery(".woocommerce-checkout-review-order-table tr:last").before('<tr class="fde_delivery"> <th> Delivery Price </th> <td data-title="delivery" style="text-align:right;"> <span><bdi>'+response.cost+'&nbsp;<span class="woocommerce-Price-currencySymbol">'+fde_ajax_object.symbol+'</span></bdi></span> </td></tr>');
				var totlal = jQuery(".woocommerce-checkout-review-order-table .order-total .woocommerce-Price-amount bdi").text().replace(',', '.');
				var sum = parseFloat(totlal) + parseFloat(response.cost);
				var price = jQuery('.order-total bdi').text(sum+' '+fde_ajax_object.symbol);
				//console.log(price);
				jQuery("form.woocommerce-checkout").css("opacity","1");
				jQuery("form.woocommerce-checkout").attr("disabled",false);

				


			});


			/*Swal.fire({
		      title: 'Fiuge Delivery <hr>',
		      html: "<form method='post' action='' class='delivery_option_process'><label> Pickup Date Time<input type='datetime-local' class='pickupDateTime' required/></label> <input type='submit' value='Submit' class='btn-success'></form>",
		      showClass: {
		        popup: 'animate__animated animate__fadeInDown'
		      },
		      hideClass: {
		        popup: 'animate__animated animate__fadeOutUp'
		      },
		      showCancelButton: false,
		      showConfirmButton: false,
		      showCloseButton:true,
		    });*/
		}else{
			alert("Something went wrong");
			return false;
		}

	});


	/*jQuery(document).on("submit",".delivery_option_process", function(e){

		e.preventDefault();

		var address = jQuery("#billing_address_1").val();
		var country = jQuery("#billing_country option:selected").val();
		var zipcode = jQuery("#billing_postcode").val();
		var pickup  = jQuery(".pickupDateTime").val();

		var data = {
			'action': 'fde_search_for_shipping_price',
			'address': address,
			'country':country,
			'zipcode':zipcode,
			'pickup':pickup,
			'dataType':'json',
		};


		jQuery.post(fde_ajax_object.ajax_url, data, function(response) {

			jQuery(".woocommerce-shipping-totals").after('<tr class="woocommerce-shipping-totals shipping"> <th> Delivery Price </th> <td data-title="delivery" style="text-align:right;"> <span><bdi>'+response.cost+'&nbsp;<span class="woocommerce-Price-currencySymbol">€</span></bdi></span> </td></tr>');
			var totlal = jQuery(".woocommerce-checkout-review-order-table .order-total .woocommerce-Price-amount bdi").text().replace(',', '.');
			console.log(parseFloat(totlal) + parseFloat(response.cost));

		});

	});*/

	var x = Cookies.get("fdeDelivery")

	jQuery(".woocommerce-table--order-details tr:last").before('<tr class="fde_delivery"> <th> Delivery Price </th> <td data-title="delivery" style="text-align:left;"> <span><bdi>'+x+'&nbsp;<span class="woocommerce-Price-currencySymbol">'+fde_ajax_object.symbol+'</span></bdi></span> </td></tr>');


});