jQuery(document).ready(function() {

jQuery(document).on('click', '.single_add_to_cart_button', function (e) {
    e.preventDefault();

    //jQuery("#em_or_not").change(function(){

    if(jQuery("#em_or_not").is(':checked')){
        
         var $thisbutton = jQuery(this),
    $form = $thisbutton.closest('form.cart'),
    id = $thisbutton.val(),
    product_qty  = $form.find('input[name=quantity]').val() || 1,
    product_id   = $form.find('input[name=product_id]').val() || id,
    variation_id = $form.find('input[name=variation_id]').val() || 0,
    emb_image    = jQuery("#emb_image").val(),
    emb_price    = jQuery("#emb_price").val(),
    firstname    = jQuery("#firstname").val(),
    lastname     = jQuery("#lastname").val(),
    placement    = jQuery("#placement").val(),
    color        = jQuery("#color").val(),
    font         = jQuery("#font").val();
    
    var data = {
        action: 'woocommerce_ajax_add_to_cart',
        product_id: product_id,
        product_sku: '',
        quantity: product_qty,
        variation_id: variation_id,
        emb_image: emb_image,
        emb_price: emb_price,
        firstname: firstname,
        lastname: lastname,
        placement:placement,
        color:color,
        font:font
    };

    jQuery(document.body).trigger('adding_to_cart', [$thisbutton, data]);

    jQuery.ajax({
        type: 'post',
        url: wc_add_to_cart_params.ajax_url,
        data: data,
        beforeSend: function (response) {
            $thisbutton.removeClass('added').addClass('loading');
        },
        complete: function (response) {
            $thisbutton.addClass('added').removeClass('loading');
        },
        success: function (response) {
          //  alert(response);
            if (response.site_url) {
                window.location = response.site_url;
                return;
            } else {
                jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
            }
        },
    });

    }//endif

    else{
        
     var $thisbutton = jQuery(this),
            $form = $thisbutton.closest('form.cart'),
            id = $thisbutton.val(),
            product_qty = $form.find('input[name=quantity]').val() || 1,
            product_id = $form.find('input[name=product_id]').val() || id,
            variation_id = $form.find('input[name=variation_id]').val() || 0;

    var data = {
        action: 'woocommerce_ajax_add_to_cart',
        product_id: product_id,
        product_sku: '',
        quantity: product_qty,
        variation_id: variation_id,
    };

    jQuery(document.body).trigger('adding_to_cart', [$thisbutton, data]);

    jQuery.ajax({
        type: 'post',
        url: wc_add_to_cart_params.ajax_url,
        data: data,
        beforeSend: function (response) {
            $thisbutton.removeClass('added').addClass('loading');
        },
        complete: function (response) {
            $thisbutton.addClass('added').removeClass('loading');
        },
        success: function (response) {
          //  alert(response);
            if (response.site_url) {
                window.location = response.site_url;
                return;
            } else {
                jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
            }
        },
    });
   
    } //endElse

    return false;
});
});