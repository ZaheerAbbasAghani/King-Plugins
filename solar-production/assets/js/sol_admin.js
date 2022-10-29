 var i = 1;
    jQuery('#add').click(function(){
        i++;
        jQuery('.pricingtr:last').after(' <tr valign="top" class="pricingtr"> <th scope="row">Pricing </th><td><input type="text" class="kwp_from"  placeholder="KWP From" style="width: 28%;border:1px solid #ddd;float: left;" /><input type="text" class="kwp_m2"  placeholder="m2" style="width: 28%;border:1px solid #ddd;float: left;" /><input type="text" class="pricing" placeholder="Price" style="width: 28%;border:1px solid #ddd;float: left;" /><button name="remove" id="'+i+'" class="button button-default btn_remove">X</button></td></tr>');
    });

    jQuery(document).on('click','.btn_remove', function(){
        jQuery(jQuery(this).parent().parent()).remove();
    });


jQuery("#sol_submit").click(function(e){
    e.preventDefault();

    var pricing = [];    
    jQuery(".pricingtr").each(function(){

        pricing.push({
            kwp_from: jQuery(this).find(".kwp_from").val(),
            kwp_m2: jQuery(this).find(".kwp_m2").val(), 
            pricing: jQuery(this).find(".pricing").val()
        });

    });
    var data = {
        'action': 'sol_insert_pricing',
        'pricing': pricing
    };

    jQuery.post(ajax_object.ajax_url, data, function(response) {
       console.log(response);
       jQuery(".solorapi").submit();
    });
}); 

jQuery('#sol_code_table').DataTable( {
    responsive: true,
    "columnDefs": [
        { "orderable": false, "targets": 0 }
    ]
});


// Delete Records
jQuery(".sol_remove").click(function(e){
    e.preventDefault();
    if (confirm("Are you sure?")) {
        var sol_id = jQuery(this).attr("id");
        var data = {
            'action': 'sol_delete_pricing',
            'sol_id': sol_id,
        };

        jQuery.post(ajax_object.ajax_url, data, function(response) {
            alert(response);
            location.reload(true);
        });
        var sol_id = jQuery(this).attr("id");
    }
     return false;
});


// Edit Records
jQuery('.sol_edit').click(function(e){
    e.preventDefault();
    jQuery("#edit_box").fadeIn();
    var edit_link = jQuery(this).attr("id");
    var edit_kwp_from = jQuery(this).attr("kwp_from");
    var edit_kwp_m2 = jQuery(this).attr("kwp_m2");
    var edit_pricing= jQuery(this).attr("pricing");
    jQuery("#kwp_from").val(edit_kwp_from);
     jQuery("#kwp_m2").val(edit_kwp_m2);
    jQuery("#pricing").val(edit_pricing);
    jQuery("#rowid").val(edit_link);
    
});


jQuery('#edit_box #cross').click(function(){
    jQuery("#edit_box").fadeOut();
});


// Update Records
jQuery(".edit_now").click(function(e){
e.preventDefault();
var kwp_from = jQuery("#kwp_from").val();
var kwp_m2 = jQuery("#kwp_m2").val();
var pricing = jQuery("#pricing").val();
var id = jQuery("#rowid").val();
var data = {
    'action': 'sol_edit_pricing',
    'kwp_from': kwp_from,
    'kwp_m2':kwp_m2,
    'pricing': pricing,
    'id': id,
};

    jQuery.post(ajax_object.ajax_url, data, function(response) {
        alert(response);
        location.reload(true);
    });
});

//Colorpicker 
//jQuery('.my-color-field').wpColorPicker();

jQuery('.my-color-field').iris({
    defaultColor: true,
    change: function(event, ui){
        var clr = jQuery(".wp-color-result").attr("style");
        jQuery("#graphColor").val(ui.color.toString());
        jQuery(this).css("background-color",ui.color.toString());
    },
    // a callback to fire when the input is emptied or an invalid color
    clear: function() {},
    // hide the color picker controls on load
    hide: true,
    // show a group of common colors beneath the square
    palettes: true
});


jQuery('.my-color-field2').iris({
    defaultColor: true,
    change: function(event, ui){
        var clr = jQuery(".wp-color-result2").attr("style");
        jQuery("#outputBoxColors").val(ui.color.toString());
        jQuery(this).css("background-color",ui.color.toString());
    },
    // a callback to fire when the input is emptied or an invalid color
    clear: function() {},
    // hide the color picker controls on load
    hide: true,
    // show a group of common colors beneath the square
    palettes: true
});

jQuery('.my-color-field3').iris({
    defaultColor: true,
    change: function(event, ui){
        var clr = jQuery(".wp-color-result3").attr("style");
        jQuery("#outputBoxTextColors").val(ui.color.toString());
        jQuery(this).css("background-color",ui.color.toString());
    },
    // a callback to fire when the input is emptied or an invalid color
    clear: function() {},
    // hide the color picker controls on load
    hide: true,
    // show a group of common colors beneath the square
    palettes: true
});

jQuery('.my-color-field4').iris({
    defaultColor: true,
    change: function(event, ui){
        var clr = jQuery(".wp-color-result4").attr("style");
        jQuery("#graphMonthColorFont").val(ui.color.toString());
        jQuery(this).css("background-color",ui.color.toString());
    },
    // a callback to fire when the input is emptied or an invalid color
    clear: function() {},
    // hide the color picker controls on load
    hide: true,
    // show a group of common colors beneath the square
    palettes: true
});


var clrval = jQuery(".my-color-field").val();
jQuery(".my-color-field").css({"background-color":clrval,"border":"none"});

var clrval2 = jQuery(".my-color-field2").val();
jQuery(".my-color-field2").css({"background-color":clrval2,"border":"none"});

var clrval3 = jQuery(".my-color-field3").val();
jQuery(".my-color-field3").css({"background-color":clrval3,"border":"none"});
var clrval4 = jQuery(".my-color-field4").val();
jQuery(".my-color-field4").css({"background-color":clrval4,"border":"none"});
