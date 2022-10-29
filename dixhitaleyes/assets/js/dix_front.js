jQuery(document).ready(function(){
  var owl = jQuery('.owl-carousel');
  owl.on('initialized.owl.carousel', function(event) {
      jQuery(".item_price").css("opacity","0.1");
      var ids = [];
      jQuery(".owl-carousel .owl-item").each(function(){
          var id = jQuery(this).find(".item_price").attr("id");
          ids.push(id);
      });

      var cookieValue = jQuery.cookie("selected_language");

      var data = {
        'action': 'dix_fetch_product_prices',
        'ids': ids,
        'selected_language':cookieValue
      };
      // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
      jQuery.post(dix_front_ajax_object.ajax_url, data, function(response) {
          jQuery.each(response.info, function(k, v) {
              var href = jQuery("#"+v.id).parent().find("a").attr("href");
              var finalUrl = href.replace(/https?:\/\/[^\/]+/i, "");
               
              jQuery("#"+v.id).parent().find("a").attr('href', jQuery("#"+v.id).parent().find("a").attr('href').replace(href, response.url+""+finalUrl));

              jQuery(".buttonbtn").eq(k).attr("href", response.url+""+finalUrl);
             
             
              jQuery("#"+v.id).text(response.symbol+v.price);
          });

          jQuery(".item_price").css("opacity","1");
      });
  });

  owl.owlCarousel({
      autoplay: false,
      rewind: true,
      navText : ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
      responsiveClass: true,
      autoHeight: true,
      nav: true,
      callbacks: true,
      responsive: {
        0: {
          items: 1,
          slideBy: 1,
        },

        600: {
          items: 3,
          slideBy: 3,
        },

        1024: {
          items: 5,
          slideBy: 5,
        },

        1366: {
          items: 5,
          slideBy: 5,
        }
      }
  });


  // Check if any language Selected


  jQuery(document).on("click", ".btn-group li a", function(e){


    e.preventDefault();
    jQuery(".item_price").css("opacity","0.1");
    var result = jQuery(this).parent("li").attr("id");


    var ids = [];
    jQuery(".owl-carousel .owl-item").each(function(){
        var id = jQuery(this).find(".item_price").attr("id");
        ids.push(id);
    });

    jQuery.cookie("selected_language", result);

    var cookieValue = jQuery.cookie("selected_language");

    var data = {
      'action': 'dix_fetch_product_prices',
      'ids': ids,
      'selected_language':cookieValue
    };
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(dix_front_ajax_object.ajax_url, data, function(response) {
        jQuery.each(response.info, function(k, v) {
            var href = jQuery("#"+v.id).parent().find("a").attr("href");
            var finalUrl = href.replace(/https?:\/\/[^\/]+/i, "");

            jQuery("#"+v.id).parent().find("a").attr('href', jQuery("#"+v.id).parent().find("a").attr('href').replace(href, response.url+""+finalUrl));

            jQuery(".buttonbtn").eq(k).attr("href", response.url+""+finalUrl);

            jQuery("#"+v.id).text(response.symbol+" "+v.price);
        });
        jQuery(".item_price").css("opacity","1");
    });

  });


setTimeout(function(){
  jQuery(".item_price").css("opacity","0.1");

  var ids = [];
  jQuery(".owl-carousel .owl-item").each(function(){
      var id = jQuery(this).find(".item_price").attr("id");
      ids.push(id);
  });

  var cookieValue = jQuery.cookie("selected_language");

  var data = {
    'action': 'dix_fetch_product_prices',
    'ids': ids,
    'selected_language':cookieValue
  };
  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
  jQuery.post(dix_front_ajax_object.ajax_url, data, function(response) {
      jQuery.each(response.info, function(k, v) {
          var href = jQuery("#"+v.id).parent().find("a").attr("href");
          var finalUrl = href.replace(/https?:\/\/[^\/]+/i, "");

          jQuery("#"+v.id).parent().find("a").attr('href', jQuery("#"+v.id).parent().find("a").attr('href').replace(href, response.url+""+finalUrl));


          jQuery(".buttonbtn").eq(k).attr("href", response.url+""+finalUrl);

           jQuery("#"+v.id).text(response.symbol+" "+v.price);
      });
      jQuery(".item_price").css("opacity","1");
  });


}, 180000);



jQuery('.owl-filter-bar').on( 'click', '.item', function() {

    var $item = jQuery(this);
    var filter = $item.data( 'owl-filter' )
    owl.owlcarousel2_filter( filter );

});


jQuery('.owl-filter-bar').on('click', 'a.item', function() {
    jQuery('.owl-filter-bar a.dix_active').removeClass('dix_active');
    jQuery(this).addClass('dix_active');
});


function get_current_page_id() {
    var page_body = jQuery('body.page');

    var id = 0;

    if(page_body) {
        var classList = page_body.attr('class').split(/\s+/);

        $.each(classList, function(index, item) {
            if (item.indexOf('page-id') >= 0) {
                var item_arr = item.split('-');
                id =  item_arr[item_arr.length -1];
                return false;
            }
        });
    }
    return id;
}


if(dix_front_ajax_object.current_page == get_current_page_id())
{
  //console.log("HELLO WORLD");

  jQuery(".item_price").css("opacity","0.1");
  var ids = [];
  jQuery(".dixGridWrapper .dixBox").each(function(){
      var id = jQuery(this).find(".item_price").attr("id");
      ids.push(id);
  });

 
  var cookieValue = jQuery.cookie("selected_language");

  var data = {
    'action': 'dix_fetch_product_prices',
    'ids': ids,
    'selected_language':cookieValue
  };
  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
  jQuery.post(dix_front_ajax_object.ajax_url, data, function(response) {
          jQuery.each(response.info, function(k, v) {
              var href = jQuery("#"+v.id).parent().find("a").attr("href");
              var finalUrl = href.replace(/https?:\/\/[^\/]+/i, "");
               
              jQuery("#"+v.id).parent().find("a").attr('href', jQuery("#"+v.id).parent().find("a").attr('href').replace(href, response.url+""+finalUrl));

              jQuery(".buttonbtn").eq(k).attr("href", response.url+""+finalUrl);
             
             
              jQuery("#"+v.id).text(response.symbol+v.price);
          });

          jQuery(".item_price").css("opacity","1");
  });


  jQuery(document).on("click", ".btn-group li a", function(e){

    e.preventDefault();
    jQuery(".item_price").css("opacity","0.1");
    var result = jQuery(this).parent("li").attr("id");


    var ids = [];
    jQuery(".dixGridWrapper .dixBox").each(function(){
        var id = jQuery(this).find(".item_price").attr("id");
        ids.push(id);
    });

    jQuery.cookie("selected_language", result);

    var cookieValue = jQuery.cookie("selected_language");

    var data = {
      'action': 'dix_fetch_product_prices',
      'ids': ids,
      'selected_language':cookieValue
    };
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
     jQuery.post(dix_front_ajax_object.ajax_url, data, function(response) {
          jQuery.each(response.info, function(k, v) {
              var href = jQuery("#"+v.id).parent().find("a").attr("href");
              var finalUrl = href.replace(/https?:\/\/[^\/]+/i, "");
               
              jQuery("#"+v.id).parent().find("a").attr('href', jQuery("#"+v.id).parent().find("a").attr('href').replace(href, response.url+""+finalUrl));

              jQuery(".buttonbtn").eq(k).attr("href", response.url+""+finalUrl);
             
             
              jQuery("#"+v.id).text(response.symbol+v.price);
          });

          jQuery(".item_price").css("opacity","1");
    });

  });


}


});