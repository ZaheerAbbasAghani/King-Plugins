jQuery(document).ready(function() {
	

jQuery(".entry-content .wp-block-gallery").append('<a id="Prev" class="navigation-arrow prev"><i class="fa fa-chevron-left"></i></a><a id="Next" class="navigation-arrow next"><i class="fa fa-chevron-right"></i></a>');
jQuery(".entry-content ul.blocks-gallery-grid li:gt(0)").hide();

jQuery('#Next').click(function(){
  jQuery('.entry-content ul.blocks-gallery-grid  li.blocks-gallery-item:first')
    .slideUp(1000)
    .next()
    .slideDown(1000)
    .end()
    .appendTo('ul.blocks-gallery-grid ');
});

jQuery('#Prev').click(function(){
  jQuery('.entry-content ul.blocks-gallery-grid  li.blocks-gallery-item:last')
    .slideDown(1000)
    .prev()
    .slideUp(1000)
    .end()
    .prependTo('ul.blocks-gallery-grid ');
	jQuery("ul.blocks-gallery-grid  li.blocks-gallery-item:gt(0)").slideUp(1000);
});





});	