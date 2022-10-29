/*jQuery(document).ready(function(){
	jQuery('.pa-color-field').each(function(){
	        jQuery(this).wpColorPicker();
	});

	jQuery('.pa-color-field').iris({
	// or in the data-default-color attribute on the input
	defaultColor: true,
	// a callback to fire whenever the color changes to a valid color
	change: function(event, ui){
		var selected_color = ui.color.toString();
		var id = jQuery(this).parents("tr").attr("id");
		jQuery("#"+id+" .thread_color").val(selected_color);
		jQuery("#"+id+" .thread_color").css("background",selected_color);

	},
	// a callback to fire when the input is emptied or an invalid color
	clear: function() {},
	// hide the color picker controls on load
	hide: true,
	// show a group of common colors beneath the square
	palettes: true
	});

	jQuery(".dynamic_colors").click(function(){
		jQuery(this).iris({
			 palettes: true
		});
	});

	//jQuery('#color-picker').iris('color', '#f33939');

});*/