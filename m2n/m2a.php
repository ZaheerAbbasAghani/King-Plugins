<?php
/*
Plugin Name: M2N
Plugin URI: https://www.fiverr.com/zaheerabbasagha
Description: Plugin is used to create print / filter functionality.
Version: 1.0
Author: Zaheer Abbas Aghani
Author URI: https://profiles.wordpress.org/zaheer01/
License: GPLv3 or later
Text Domain: m2n
Domain Path: /languages
*/

defined("ABSPATH") or die("No direct access!");
class M2N {

function __construct() {
	//add_action('init', array($this, 'bpem_start_from_here'));
	add_action("admin_enqueue_scripts",array($this,"enqueue_custom_script"));
	add_filter( 'woocommerce_package_rates', array($this,'my_hide_shipping_when_free_is_available'), 100 );
	add_action( 'woocommerce_thankyou', array($this,'woocommerce_thankyou_change_order_status'), 10, 1 );
	add_filter( 'body_class', array($this,'add_body_classes'));
	add_action( 'manage_shop_order_posts_custom_column',array($this,'prdd_column_value'));
	remove_action( 'woocommerce_archive_description', array($this,'woocommerce_taxonomy_archive_description'), 10 );
	add_action( 'woocommerce_after_shop_loop', array($this,'woocommerce_taxonomy_archive_description'), 100 );
	add_filter( 'manage_edit-shop_order_columns',array($this,'delivery_data_columns') );
	add_action('pre_get_posts', array($this,'shop_order_column_meta_field_sortable_orderby') );
	add_filter( 'woocommerce_shop_order_search_fields', array($this,'shipping_postcode_searchable_field') );
	add_action( "init", array($this,"print_pdf"));
	add_action( "init", array($this,"voucher_fun"));
}



/*function bpem_start_from_here() {
	require_once plugin_dir_path(__FILE__) . 'cv19_front/cv19_shortcode.php';
}*/

// Load CSS
function enqueue_custom_script() {

	wp_enqueue_script( "cs-script", plugins_url('assets/js/script.js', __FILE__), array( 'jquery' ),"1.0.0", true);
/*wp_enqueue_script('cv19-script', plugins_url('assets/js/cv19.js', __FILE__),array('jquery'),'1.0.0', true);*/
	wp_enqueue_script( "cs-datatable", 'https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js', array( 'jquery' ),"1.10.22", true);
	wp_enqueue_style("cs-datatable", 'https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css', array( 'jquery' ), '1.10.22', 'all' ); 
}


function my_hide_shipping_when_free_is_available( $rates ) {
  $free = array();
  foreach ( $rates as $rate_id => $rate ) {
    if ( 'free_shipping' === $rate->method_id ) {
      $free[ $rate_id ] = $rate;
      break;
    }
  }
  return ! empty( $free ) ? $free : $rates;
}




function woocommerce_thankyou_change_order_status( $order_id ){
    if( ! $order_id ) return;

    $order = wc_get_order( $order_id );

    if( $order->get_status() == 'on-hold' )
        $order->update_status( 'processing' );
}



function add_body_classes( $classes ) {
  global $post;
  // product pages only
  if ( is_product() ) {
    // get the categories for this product
    $terms = get_the_terms( $post->ID, 'product_cat' );
    foreach ($terms as $term) {
      // you can use either slug or id, no point having both
      $classes[] = 'categorie-id-'.$term->term_id;
    }
    return $classes;
  }
}



function delivery_data_columns( $columns ) {

   $new_columns = ( is_array( $columns ) ) ? $columns : array();
    unset( $new_columns[ 'order_total' ] );
    unset( $new_columns[ 'wc_actions' ] );

    $new_columns['prdd_data']   = __( 'Gekozen levertijd ');

    $new_columns[ 'order_total' ]  = $columns[ 'order_total' ];
    $new_columns[ 'wc_actions' ]   = $columns[ 'wc_actions' ];

    return $new_columns;

}



function prdd_column_value( $column ) {

    global $post;

    $order = wc_get_order( $post->ID );
    $items = $order->get_items();

    foreach( $items as $item_key => $item_value ) {
        $item_meta = $item_value->get_meta_data();

        if ( $column == 'prdd_data' ) {

            $prdd_date = $prdd_time = $prdd_start_date = "";

            foreach( $item_meta as $meta_data ) {


                if ('_prdd_date' === $meta_data->key) {
                    $prdd_date .= $meta_data->value;
                    $prdd_start_date  = date( 'Y-m-d', strtotime( $prdd_date ) );
                }

               if( '_prdd_time_slot' === $meta_data->key ) {
                    $prdd_time .= $meta_data->value;

                }
            }

            echo $prdd_start_date ."<br>". $prdd_time;
        }
    }
}


// Sorteren op datum

function shop_order_column_meta_field_sortable_orderby( $query ) {
    global $pagenow;

    if ( 'edit.php' === $pagenow && isset($_GET['post_type']) && 'shop_order' === $_GET['post_type'] ){

        $orderby  = $query->get( 'orderby');
        $meta_key = '_prdd_date';

        if ('title_prdd_date' === $orderby){

       	$query->set( 'post_type', 'shop_order' );
        $query->set( 'key', '_prdd_date' );
        $query->set( 'order', 'DESC');
        $query->set( 'orderby', 'date' );

        $query->set( 'order', 'ASC');
        $query->set( 'meta_query', array(
            array(
                'key'     => '_prdd_date',
            )
        ) );


        }
    }
}

// Metaeky zoekbaar maken

function shipping_postcode_searchable_field( $meta_keys ){
    $meta_keys[] = '_prdd_date';
    return $meta_keys;
}

// Code uit mail verwijderen van tegoedbon
function voucher_fun() {
  	if ( ! function_exists( 'wc_pdf_product_vouchers' ) ) {
    return;
  }
  remove_action( 'woocommerce_order_item_meta_start', array( wc_pdf_product_vouchers()->get_voucher_handler_instance(), 'order_item_meta_start' ), 10, 3 );
}


function print_pdf(){
 	if(isset($_GET['spost_id'])) {
 		require_once get_stylesheet_directory().'/fpdf182/fpdf.php';
 		//header( 'Content-Type: text/html; charset=ISO-8859-15' );

 		$order_id = $_GET['spost_id'];
 		$afterDash = substr($order_id, strpos($order_id, "-") + 1);

		$order = wc_get_order( $afterDash);
		$items = $order->get_items();

		foreach( $items as $item_key => $item_value ) {
			$item_meta = $item_value->get_meta_data();

			foreach( $item_meta as $meta_data ) {
				if ('_Schrijf hier je kaartje' === $meta_data->key) {

	                $custom_data .= wp_strip_all_tags($meta_data->value);
	                $str = iconv('UTF-8', 'windows-1252', $custom_data);
	               	$final = html_entity_decode($str ,ENT_QUOTES, 'windows-1252');
	          		$pdf = new FPDF('P','mm',array(100,100));
	          		header('Content-type: application/pdf');
					$pdf->SetMargins(5, 5);
					$pdf->AddPage();
					$pdf->SetFont('Arial','',14);
					$pdf->MultiCell(95, '9', $final, '', 'L');
					ob_get_clean();
					$pdf->Output($afterDash.".pdf", "I");
					exit;

	           }
	       }
		}

 	}

}


} // class ends

// CHECK WETHER CLASS EXISTS OR NOT.

if (class_exists('M2N')) {
	$obj = new M2N();
}