jQuery(document).ready(function(){

//jQuery("#emb_frm").validate();

jQuery("#btnEmbroidery a").click(function(e){
	e.preventDefault();	
	var image = jQuery(".slick-track div.rtwpvg-gallery-image:last img").attr("src");
	jQuery(".leftSideImage img").attr("src",image);
});



jQuery('#firstLine').on('keyup', function(){
    jQuery('.imgCover p').html( jQuery(this).val() );
});

jQuery('#secondLine').on('keyup', function(){
    jQuery('.imgCover span').html( jQuery(this).val() );
});

jQuery("#exampleFormControlSelect1").on('change',function(){
		if(jQuery(this).val() == "Left"){
			
			jQuery(".imgCover").css({"right":"0px","padding-right":"20%","left":"auto","padding-left":"0px"});
			jQuery(this).addClass("active");
			
		}
		else{
			

			jQuery(".imgCover").css({"left":"0px","padding-left":"20%","right":"auto"});
			jQuery(this).addClass("active");
		}

});

jQuery("#exampleFormControlSelect2").on('change',function(){
			var clr = jQuery(this).children("option:selected").attr("color-data");
			jQuery(".imgCover p, .imgCover span").css({"color":clr});
			jQuery(this).addClass("active");
		
});

jQuery("#exampleFormControlSelect3").on('change',function(){
		if(jQuery(this).val() == "Script"){
			jQuery(".imgCover p, .imgCover span").css({"font-family":"Dancing Script","font-size":"20px","font-weight":"bolder"});
			jQuery(this).addClass("active");
		}
		else{
			jQuery(".imgCover p, .imgCover span").css({"font-family":"inherit","font-size":"14px"});
			jQuery(this).addClass("active");
		}
});



jQuery('body').on('click', '.customize_now' ,function(e){
	e.preventDefault();

	var firstLine = jQuery("#firstLine").val();
	var secondLine = jQuery("#secondLine").val();
	var sides = jQuery("#exampleFormControlSelect1").val();
	var colors = jQuery("#exampleFormControlSelect2").val();
	var fontStyle = jQuery("#exampleFormControlSelect3").val();
	var old_price = jQuery(".woocommerce-variation-price").text().replace('$', '');
	//var custom_price = jQuery("#btnEmbroidery .woocommerce-Price-amount").text().replace('$', '');
	var custom_price = 0;
	
	
	if(firstLine==""){
		alert("First Line is required");
		return false;
	}else{
		custom_price = parseFloat(custom_price) + 9;
	}

	if(secondLine!==""){
		//alert("First Line is required");
		custom_price = parseFloat(custom_price) + 5;
	}
	
	if(!jQuery("#btnEmbroidery").hasClass("paid")){
		var price = parseFloat(old_price) + parseFloat(custom_price);
		jQuery(".woocommerce-variation-price span").html("$"+price);
	}
	jQuery("#btnEmbroidery").hide("fast");
	jQuery(".selected_embroidery").hide();
	jQuery("#btnEmbroidery").after("<div class='selected_embroidery'><span><input type='checkbox' id='em_or_not' checked/></span> <span>ADD EMBROIDERY</span> | <span><a href='#' data-toggle='modal' data-target='#exampleModal' class='pa_edit'> EDIT </a></span> <a href='#' style='float:right;' id='pa_delete'> Delete </a><span style='float:right;margin-right:15px;' class='woocommerce-Price-amount'>$"+custom_price+" </span> <div class=''><label>First Line: "+firstLine+"</label><br><label>Second Line: "+secondLine+"</label><br><label> Placement: "+sides+" | </label> Color: "+colors+" | Font: "+fontStyle+"</div></div>");
	if(!jQuery("#btnEmbroidery").hasClass("paid")){
		jQuery("#btnEmbroidery").addClass("paid");
	}

		var final_price = parseFloat(old_price) + parseFloat(custom_price);
		var image = jQuery(".slick-track div.rtwpvg-gallery-image:last img").attr("src");
		jQuery("#emb_image").val(image);	
		jQuery("#emb_price").val(final_price);
		jQuery("#firstname").val(firstLine);
		jQuery("#lastname").val(secondLine);
		jQuery("#placement").val(sides);
		jQuery("#color").val(colors);
		jQuery("#font").val(fontStyle);

		jQuery(".kingModal").hide();
		jQuery(".modal-backdrop").hide();

});


jQuery(document).on("click", "a.pa_edit" , function(e) {
	e.preventDefault();
	var image = jQuery(".slick-track div.rtwpvg-gallery-image:last img").attr("src");
	jQuery(".leftSideImage img").attr("src",image);
});


jQuery("#pa_color").on('change',function(){
	jQuery("#btnEmbroidery").removeClass("paid");
});

jQuery("#pa_size").on('change',function(){
	jQuery("#btnEmbroidery").removeClass("paid");
});


jQuery(function() {
    jQuery('#reset').click(function() {
        jQuery(".lines").val("");
    });
});

jQuery(document).on("click", "a#pa_delete" , function(e) {	
	e.preventDefault();
	jQuery(".selected_embroidery").hide();
	jQuery("#btnEmbroidery").show();
});



});