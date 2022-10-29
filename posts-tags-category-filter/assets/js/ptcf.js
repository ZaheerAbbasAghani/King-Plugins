jQuery(document).ready(function(){

jQuery("#nav ul li a").click(function(e){
e.preventDefault();
jQuery(".listofposts").css("opacity","0.5");
var term_id = jQuery(this).attr("term-id");
var group = jQuery(this).attr("group");
var current = jQuery(this).text();
jQuery(this).parent().parent().parent().parent().parent().hide();
if(jQuery(this).hasClass("orders")){

    var g_tag = jQuery(".choosenValue a").text();
    var g_term_id = jQuery(".choosenValue a").attr("data-id");
    var tp = current;
    var data = {
        'action': 'ptcf_filter_orders',
        'group':g_tag,
        'g_term':g_term_id,
        'type': tp
    };
}else{
    jQuery(".choosenValue").html("<a href='#' data-id='"+group+"'>"+current+"</a>");
    var data = {
        'action': 'ptcf_filter_categories',
        'term_id': term_id,      // We pass php values differently!
        'group':group
    };
}   
// We can also pass the url value separately from ajaxurl for front end AJAX implementations
jQuery.post(ajax_object.ajax_url, data, function(response) {
    jQuery(".listofposts").css("opacity","1");
    jQuery(".listofposts").html(response);
    jQuery(".subs div").hide();
});


});

jQuery("#nav > li > a").click(function (e) { // binding onclick
	e.preventDefault();
    if (jQuery(this).parent().hasClass('selected')) {
        jQuery("#nav .selected div div").slideUp(100); // hiding popups
        jQuery("#nav .selected").removeClass("selected");
    } else {
        jQuery("#nav .selected div div").slideUp(100); // hiding popups
        jQuery("#nav .selected").removeClass("selected");

        if (jQuery(this).next(".subs").length) {
            jQuery(this).parent().addClass("selected"); // display popup
            jQuery(this).next(".subs").children().slideDown(200);
        }
    }
}); 

jQuery(".ptcf_box").slice(0, 5).show();
jQuery(".misha_loadmore").on('click', function (e) {
    e.preventDefault();
    jQuery(".ptcf_box:hidden").slice(0, 5).slideDown();
    if (jQuery(".ptcf_box:hidden").length == 0) {
        jQuery(".misha_loadmore").fadeOut('fast');
    }
    jQuery('html,body').animate({
        scrollTop: jQuery(this).offset().top
    }, 1000);
});

});