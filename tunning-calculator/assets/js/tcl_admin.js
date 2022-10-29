jQuery(document).ready(function(){
	// Color 1
	jQuery('.my-color-field').iris({
		// or in the data-default-color attribute on the input
		defaultColor: true,
		// a callback to fire whenever the color changes to a valid color
		change: function(event, ui){
			var element = event.target;
	        var color = ui.color.toString();
	       // jQuery("#gg").val(color);
	        jQuery(".my-color-field").css("background",color);
	        //console.log(color);
		},
	});

	var tcl_color_field = jQuery(".my-color-field").val();
	jQuery(".my-color-field").css("background",tcl_color_field);

	// Color 2
	jQuery('.my-color-field2').iris({
		// or in the data-default-color attribute on the input
		defaultColor: true,
		// a callback to fire whenever the color changes to a valid color
		change: function(event, ui){
			var element = event.target;
	        var color = ui.color.toString();
	       // jQuery("#gg").val(color);
	        jQuery(".my-color-field2").css("background",color);
	        //console.log(color);
		},
	});

	var tcl_color_field = jQuery(".my-color-field2").val();
	jQuery(".my-color-field2").css("background",tcl_color_field);

	// Color 2
	jQuery('.my-color-field3').iris({
		// or in the data-default-color attribute on the input
		defaultColor: true,
		// a callback to fire whenever the color changes to a valid color
		change: function(event, ui){
			var element = event.target;
	        var color = ui.color.toString();
	       // jQuery("#gg").val(color);
	        jQuery(".my-color-field3").css("background",color);
	        //console.log(color);
		},
	});

	var tcl_color_field = jQuery(".my-color-field3").val();
	jQuery(".my-color-field3").css("background",tcl_color_field);

	jQuery('body').on('click', '#tcl_delete_all', function(e) {
    	if(confirm("Are you sure you want to delete data?")){
            var data = {
                'action': 'tcl_delete_database_records',
                'delete_all': 1
            };

            jQuery.post(tcl_object.ajax_url, data, function(response) {
                alert(response);
            });
        }
        else{
            return false;
        }
    });

});