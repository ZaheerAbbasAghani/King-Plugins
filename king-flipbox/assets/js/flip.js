jQuery(document).ready(function(){


jQuery('.bx').on('mouseover', function(){
	//jQuery(this).hide();
	//jQuery(this).stop().fadeTo('slow',1);
	jQuery(this).stop().animate({ "opacity": 0 },1000);
})
jQuery('.bx').on('mouseout', function(){
	jQuery(this).stop().animate({ "opacity": 1 },1000);
	//jQuery(this).stop().fadeTo('slow',0);
});	



});