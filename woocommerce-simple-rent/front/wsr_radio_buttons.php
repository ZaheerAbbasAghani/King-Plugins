<?php 

function variation_radio_buttons($html, $args) {
  $args = wp_parse_args(apply_filters('woocommerce_dropdown_variation_attribute_options_args', $args), array(
    'options'          => false,
    'attribute'        => false,
    'product'          => false,
    'selected'         => false,
    'name'             => '',
    'id'               => '',
    'class'            => '',
    'show_option_none' => __('Choose an option', 'woocommerce'),
  ));

  if(false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product) {
    $selected_key     = 'attribute_'.sanitize_title($args['attribute']);
    $args['selected'] = isset($_REQUEST[$selected_key]) ? wc_clean(wp_unslash($_REQUEST[$selected_key])) : $args['product']->get_variation_default_attribute($args['attribute']);
  }

  $options               = $args['options'];
  $product               = $args['product'];
  $attribute             = $args['attribute'];
  $name                  = $args['name'] ? $args['name'] : 'attribute_'.sanitize_title($attribute);
  $id                    = $args['id'] ? $args['id'] : sanitize_title($attribute);
  $class                 = $args['class'];
  $show_option_none      = (bool)$args['show_option_none'];
  $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __('Choose an option', 'woocommerce');

  if(empty($options) && !empty($product) && !empty($attribute)) {
    $attributes = $product->get_variation_attributes();
    $options    = $attributes[$attribute];
  }

  $radios = '<div class="variation-radios">';

  if(!empty($options)) {
    if($product && taxonomy_exists($attribute)) {
      $terms = wc_get_product_terms($product->get_id(), $attribute, array(
        'fields' => 'all',
      ));

      foreach($terms as $term) {
        if(in_array($term->slug, $options, true)) {
          $radios .= '<input type="radio" name="'.esc_attr($name).'" value="'.esc_attr($term->slug).'" '.checked(sanitize_title($args['selected']), $term->slug, false).'><label for="'.esc_attr($term->slug).'">'.esc_html(apply_filters('woocommerce_variation_option_name', $term->name)).'</label>';
        }
      }
    } else {
      foreach($options as $option) {
        $checked    = sanitize_title($args['selected']) === $args['selected'] ? checked($args['selected'], sanitize_title($option), false) : checked($args['selected'], $option, false);
        $radios    .= '<input type="radio" name="'.esc_attr($name).'" value="'.esc_attr($option).'" id="'.sanitize_title($option).'" '.$checked.'><label for="'.sanitize_title($option).'">'.esc_html(apply_filters('woocommerce_variation_option_name', $option)).'</label>';
      }
    }
  }

  $radios .= '</div>';

  return $html.$radios;
}
add_filter('woocommerce_dropdown_variation_attribute_options_html', 'variation_radio_buttons', 20, 2);

function variation_check($active, $variation) {
  if(!$variation->is_in_stock() && !$variation->backorders_allowed()) {
    return false;
  }
  return $active;
}
add_filter('woocommerce_variation_is_active', 'variation_check', 10, 2);


// creating Date Field
function add_custom_fees_before_add_to_cart() {
    global $product;

  	$args1 = array(
        'type' => 'text',
        'class' => array( 'form-row-wide' ),
        'placeholder' => 'Select date...',
        'default' => '',
        'name'    => "from_date",

    );
    ?>
    <div style="height: auto;overflow: hidden;">
    <div class="custom-date-wrap">
        <label for="iconic-engraving"><?php _e( 'From Date: ', 'textdomain' ); ?> <?php woocommerce_form_field( 'from_date', $args1, '' ); ?></label>
       
    </div>

     <?php  $args2 = array(
        'type' => 'text',
        'class' => array( 'form-row-wide' ),
        'placeholder' => '',
        'default' => '',
        'name'    => "to_date",

    );
    ?>
    <div class="custom-date-wrap">
        <label for="iconic-engraving"><?php _e( 'To Date: ', 'textdomain' ); ?> <?php woocommerce_form_field( 'to_date', $args2, '' ); ?></label>
       
    </div>
    </div>
<?php 
}

add_action( 'woocommerce_before_add_to_cart_button', 'add_custom_fees_before_add_to_cart', 99 );


/*---------------------------------------------------------------
* Add as custom cart item data
---------------------------------------------------------------*/
add_filter( 'woocommerce_add_cart_item_data', 'add_custom_cart_item_data', 10, 21 );
function add_custom_cart_item_data($cart_item_data, $product_id, $variation_id ){

    if( isset( $_POST['from_date'] ) ) {
    	$cart_item_data = array();

        $from_date = strtotime("-2 day",strtotime($_POST['from_date']));
        $to_date=strtotime("+2 day",strtotime($_POST['to_date']));

        $cart_item_data['save_from_date']=date("Y-m-d", $from_date);
        $cart_item_data['save_to_date'] = date("Y-m-d",$to_date);
    }

    
    //print_r($cart_item_data);

    return $cart_item_data;
}
/*---------------------------------------------------------------
* Add custom fields values under cart item name in cart
---------------------------------------------------------------*/

add_filter( 'woocommerce_cart_item_name', 'date_custom_field', 10, 21 );
function date_custom_field( $item_name, $cart_item, $cart_item_key ) {
    if( ! is_cart() )
        return $item_name;

    if( isset($cart_item['save_from_date']) ) {

      $f_date = strtotime("+2 day",strtotime($cart_item['save_from_date']));

        $item_name .= '<div class="my-custom-class"><strong>' . __("<b> From Date </b>", "woocommerce") . ':</strong> ' . date("Y-m-d",$f_date) . '</div>'; 
    } 

    if( isset($cart_item['save_to_date']) ) {
        $t_date = strtotime("-2 day",strtotime($cart_item['save_to_date']));

        $item_name .= '<div class="my-custom-class"><strong>' . __("<b> To Date </b>", "woocommerce") . ':</strong> ' . date("Y-m-d",$t_date)  . '</div>'; 
    } 

    return $item_name;
}

/*---------------------------------------------------------------
* Display date custom fields values under item name in checkout
---------------------------------------------------------------*/

add_filter( 'woocommerce_checkout_cart_item_quantity', 'date_custom_checkout_cart_item_name', 10, 21 );
function date_custom_checkout_cart_item_name( $item_qty, $cart_item, $cart_item_key ) {
    if( isset($cart_item['save_from_date']) ) {

       $f_date = strtotime("+2 day",strtotime($cart_item['save_from_date']));

        $item_qty .= '<div class="my-custom-class"><strong>' . __("From Date", "woocommerce") . ':</strong> ' .  date("Y-m-d",$f_date) . '</div>';
    }

    if( isset($cart_item['save_to_date']) ) {
       $t_date = strtotime("-2 day",strtotime($cart_item['save_to_date']));
       
        $item_qty .= '<div class="my-custom-class"><strong>' . __("To Date", "woocommerce") . ':</strong> ' . date("Y-m-d",$t_date) . '</div>';
    }

    return $item_qty;
}

/*---------------------------------------------------------------
* Save chosen slelect field value to each order item as custom meta data and display it everywhere
---------------------------------------------------------------*/
add_action('woocommerce_checkout_create_order_line_item', 'save_order_item_product_fitting_color', 10, 21 );
function save_order_item_product_fitting_color( $item, $cart_item_key, $values, $order ) {
    if( isset($values['save_from_date']) ) {
        $key = __('From Date', 'woocommerce');
        $value = $values['save_from_date'];
        $item->update_meta_data( $key, $value ,$item->get_id());
    }

     if( isset($values['save_to_date']) ) {
        $key = __('To Date', 'woocommerce');
        $value = $values['save_to_date'];
        $item->update_meta_data( $key, $value ,$item->get_id());
    }
}