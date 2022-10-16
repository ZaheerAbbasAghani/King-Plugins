<?php 
/**
 * @snippet       WooCommerce Add New Tab @ My Account
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 5.0
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
// ------------------
// 1. Register new endpoint (URL) for My Account page
// Note: Re-save Permalinks or it will give 404 error
  
function sqr_add_premium_support_endpoint() {
    add_rewrite_endpoint( 'sqr-reservation', EP_ROOT | EP_PAGES );
}
  
add_action( 'init', 'sqr_add_premium_support_endpoint' );
  
// ------------------
// 2. Add new query var
  
function sqr_premium_support_query_vars( $vars ) {
    $vars[] = 'sqr-reservation';
    return $vars;
}
  
add_filter( 'query_vars', 'sqr_premium_support_query_vars', 0 );
  
// ------------------
// 3. Insert the new endpoint into the My Account menu
  
function sqr_add_premium_support_link_my_account( $items ) {
    $items['sqr-reservation'] = 'Reservation';
    return $items;
}
  
add_filter( 'woocommerce_account_menu_items', 'sqr_add_premium_support_link_my_account' );
  
// ------------------
// 4. Add content to the new tab
  
function sqr_premium_support_content() {
   
   echo '<h3>Premium WooCommerce Support</h3><p>Welcome to the WooCommerce support area. As a premium customer, you can submit a ticket should you have any WooCommerce issues with your website, snippets or customization. <i>Please contact your theme/plugin developer for theme/plugin-related support.</i></p>';
  
}
  
add_action( 'woocommerce_account_sqr-reservation_endpoint', 'sqr_premium_support_content' );
// Note: add_action must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format