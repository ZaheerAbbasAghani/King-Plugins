jQuery(document).ready(function(){
	// TCL MODEL
	jQuery('#tcl_make').change(function() {
	    var $option=jQuery(this).find('option:selected');
	    var value = $option.val();

	    var data = {
			'action': 'tcl_selected_make',
			'make': value
		};

		jQuery.post(tcl_object.ajax_url, data, function(response) {
			if(jQuery("#tcl_model").length == 0){
				jQuery("#tcl_make").after(response);
			}else{
				jQuery('#tcl_model').remove();
				jQuery("#tcl_make").after(response);
			}
		});
	});

	// TCL MODEL
	jQuery('body').on('change', '#tcl_model', function() {
	//jQuery('#tcl_model').change(function() {
	    var option=jQuery(this).find('option:selected');
	    var value = option.val();
	    //alert(value);
	    var data = {
			'action': 'tcl_selected_trim',
			'model': value
		};

		jQuery.post(tcl_object.ajax_url, data, function(response) {
			if(jQuery("#tcl_trim").length == 0){
				jQuery("#tcl_model").after(response);
			}else{
				jQuery('#tcl_trim').remove();
				jQuery("#tcl_model").after(response);
			}
			
		});
	});

	// TCL MODEL
	jQuery('body').on('change', '#tcl_trim', function() {
		

		if(jQuery(".start_your_engine").length == 0){
				jQuery(".dropdown_inner").after("<a href='#' class='button start_your_engine mkdf-btn mkdf-btn-medium mkdf-btn-solid'> Start Your Engine </a>");
			}else{
				jQuery('.start_your_engine').remove();
				jQuery(".dropdown_inner").after("<a href='#' class='button start_your_engine mkdf-btn mkdf-btn-medium mkdf-btn-solid'> Start Your Engine </a>");
		}

		jQuery(".start_your_engine").text(tcl_object.tcl_button_text);		
	});

	jQuery('body').on('click', '.start_your_engine', function(e) {
		e.preventDefault();

		var make = jQuery("#tcl_make option:selected").val();
		var model = jQuery("#tcl_model option:selected").val();
		var trim = jQuery("#tcl_trim option:selected").val();

		//alert(tcl_object.max_limit);
	
		var data = {
			'action': 'tcl_start_your_engine',
			'make': make,
			'model': model,
			'trim':trim
		};

		jQuery.post(tcl_object.ajax_url, data, function(response) {
			var stock_1_power=response.stock_power;
			var stage_1_power = response.stage_1_power;
			var stage_2_power = response.stage_2_power;
			var stage_1_price = response.stage_1_price;

			var stock_torque = response.stock_torque;
			var stage_1_torque = response.stage_1_torque;
			var stage_2_torque = response.stage_2_torque;
			var stage_2_price = response.stage_2_price;

			var power_torque = [stock_1_power,stage_1_power,stage_2_power,stage_1_price,stock_torque,stage_1_torque,stage_2_torque,stage_2_price];
			
			jQuery(".js-gauge").remove();
			var count=Object.keys(response).length;

			for(i=0; i<count; i++){
				if(i == 0){
					jQuery(".tcl_dropdown_list").append('<div class="js-gauge power">POWER</div>');
				}
				if(i == 4){
					jQuery(".tcl_dropdown_list").append('<div class="js-gauge power">TORQUE</div>');
				}
				jQuery(".tcl_dropdown_list").append('<div class="js-gauge js-gauge--'+i+' gauge"></div>');

			if(i>=0 && i<=2){
				
				jQuery('.js-gauge--'+i).kumaGauge({
					value : power_torque[i],
					gaugeBackground : tcl_object.tcl_gauge_background,
					background : tcl_object.tcl_background,
					gaugeWidth : 10,
					showNeedle : false,
					fill :tcl_object.tcl_background_fill,
                    min: 0,
                    max: tcl_object.max_limit,
					label : {
				        display : true,
				        left : '',
				        right : '',
				        fontColor : '#1E4147',
				        fontSize : '11',
				        fontWeight : 'bold',
                        fontFamily: 'inherit',
				    }	
				});

			}else{
				if(i==3){
					jQuery(".js-gauge--"+i).html("<h4> $"+power_torque[i]+"</h4>");
				}
				if(i==7){
					jQuery(".js-gauge--"+i).html("<h4> $"+power_torque[i]+"</h4>");
				}
			}

			if(i>=4 && i<=6){
				
				jQuery('.js-gauge--'+i).kumaGauge({
					value : power_torque[i],
					gaugeBackground : tcl_object.tcl_gauge_background,
					background : tcl_object.tcl_background,
					gaugeWidth : 10,
					showNeedle : false,
					fill :tcl_object.tcl_background_fill,
                    min: 0,
                    max: tcl_object.max_limit,
					label : {
				        display : true,
				        left : '',
				        right : '',
				        fontColor : '#1E4147',
				        fontSize : '11',
				        fontWeight : 'bold',
                        fontFamily: 'inherit',
				    }	
				});

			}

				if(i != 3 && i<=3){
					jQuery(".js-gauge--"+i+" text tspan:first").append(" "+tcl_object.tcl_power_labels);
                    
				}

				if(i>=4 && i<=6){
					jQuery(".js-gauge--"+i+" text tspan:first").append(" "+tcl_object.tcl_torque_labels);
				}
			}

			jQuery(".tcl_dropdown_list .power:first").prepend("<h5 style='visibility:hidden;'>Stock</h5>");
		
			jQuery(".js-gauge--0").prepend("<h5 style='text-align:center;'>Stock</h5>");
			jQuery(".js-gauge--1").prepend("<h5 style='text-align:center;'>Stage 1</h5>");
			jQuery(".js-gauge--2").prepend("<h5 style='text-align:center;'> Stage 2</h5>");
			
			var gauge3 =jQuery(".js-gauge--3 h4").text();
			var gauge7 =jQuery(".js-gauge--7 h4").text();
			jQuery(".js-gauge--0 h5").after("<span style='visibility:hidden;'>"+gauge3+"</span>");
  			jQuery(".js-gauge--1 h5").after("<span>"+gauge3+"</span>");
  			jQuery(".js-gauge--2 h5").after("<span>"+gauge7+"</span>");
			jQuery(".start_your_engine").text(tcl_object.tcl_button_text);


			var stage1img = response.stage1img;
			var stage2img = response.stage2img;
			var url = response.learnmoreUrl;

			jQuery(".dynograph").remove();

			if(stage1img != ""){
				jQuery(".tcl_dropdown_list").append("<div class='dynograph' style='margin-top:50px;clear:both;'> <h3 style='text-align:center;'> Stage 1 </h3><img src='"+stage1img+"'></div>");
			}

			if(stage2img != ""){
				jQuery(".tcl_dropdown_list").append("<div class='dynograph' style='margin-top:50px;'><h3 style='text-align:center;'> Stage 2 </h3><img src='"+stage2img+"'></div>");
			}

			if(stage2img != ""){
				jQuery(".tcl_dropdown_list").append('<div class="dynograph"><a href="'+url+'" class="button mkdf-btn mkdf-btn-medium mkdf-btn-solid" target="_blank">Learn More </a></div>');
			}

		});
	});



});