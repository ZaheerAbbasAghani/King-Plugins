jQuery(document).ready(function() {
	var codemirror_textarea = document.getElementById("all_classes");
	var editor = CodeMirror.fromTextArea(codemirror_textarea, {
    	lineNumbers: true,
    	matchBrackets: true,
        mode:  "javascript",
        lineWrapping: true,
        theme: "xq-dark"
	});


	var value = "; " + document.cookie;
	var parts = value.split("; " + "all_classes" + "=");
	var words = parts[1].split(",");
	var allclasses = [];
	for ( var i = 0, l = words.length; i < l; i++ ) {
    	//"body.dark-mode ."+words[ i ]+", ";
    	 allclasses.push("body.dark-mode ."+words[ i ]);
	}
	allclasses.pop();
	jQuery(".generate_classes").click(function(e) {
		e.preventDefault();
		var x = allclasses.toString();
		//console.log(x)
		//editor.setValue(x.slice(0, -1));
		editor.setValue(x);


	});



 
    // Add Color Picker to all inputs that have 'color-field' class
    /*jQuery(function() {
        jQuery('#ednm_color_picker').wpColorPicker();
    });*/
     
/*jQuery("#ednm_color_picker").wpColorPicker(
      change: function (event, ui) {
        var element = event.target;
        var color = ui.color.toString();
        console.log(color);
        // Add your code here
    }

);*/


jQuery('#ednm_color_picker_val').wpColorPicker({
	defaultColor: true,
    change: function (event, ui) {
        //var element = event.target;
        var color = ui.color.toString();
        jQuery("#ednm_color_picker_val").val(color);
        // Add your code here
    },

});


	
});