<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '2.2.0' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_load_textdomain', [ true ], '2.0', 'hello_elementor_load_textdomain' );
		if ( apply_filters( 'hello_elementor_load_textdomain', $hook_result ) ) {
			load_theme_textdomain( 'hello-elementor', get_template_directory() . '/languages' );
		}

		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_register_menus', [ true ], '2.0', 'hello_elementor_register_menus' );
		if ( apply_filters( 'hello_elementor_register_menus', $hook_result ) ) {
			register_nav_menus( array( 'menu-1' => __( 'Primary', 'hello-elementor' ) ) );
		}

		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_add_theme_support', [ true ], '2.0', 'hello_elementor_add_theme_support' );
		if ( apply_filters( 'hello_elementor_add_theme_support', $hook_result ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				array(
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
				)
			);
			add_theme_support(
				'custom-logo',
				array(
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				)
			);

			/*
			 * Editor Style.
			 */
			add_editor_style( 'editor-style.css' );

			/*
			 * WooCommerce.
			 */
			$hook_result = apply_filters_deprecated( 'elementor_hello_theme_add_woocommerce_support', [ true ], '2.0', 'hello_elementor_add_woocommerce_support' );
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', $hook_result ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		$enqueue_basic_style = apply_filters_deprecated( 'elementor_hello_theme_enqueue_style', [ true ], '2.0', 'hello_elementor_enqueue_style' );
		$min_suffix          = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( apply_filters( 'hello_elementor_enqueue_style', $enqueue_basic_style ) ) {
			wp_enqueue_style(
				'hello-elementor',
				get_template_directory_uri() . '/style' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				get_template_directory_uri() . '/theme' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		wp_enqueue_style("jplayer.blue.monday", get_template_directory_uri().'/assets/dist/skin/blue.monday/css/jplayer.blue.monday.css','', 1.0, 'all' );

		wp_enqueue_style("mystyle", get_template_directory_uri().'/assets/myStyle.css','', 1.0, 'all' );

		wp_enqueue_script( "jplayer", get_template_directory_uri().'/assets/dist/jplayer/jquery.jplayer.min.js', array( 'jquery' ), 1.0, true );		
		wp_enqueue_script( "jplayerList", get_template_directory_uri().'/assets/dist/add-on/jplayer.playlist.min.js', array( 'jquery' ), 1.0, true );		

		//wp_enqueue_script( "allfonts", 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/js/all.min.js', array( 'jquery' ), 1.0, true );		

//		wp_enqueue_script("jqueryUi", 'http://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css', array( '' ), false, true );

	wp_enqueue_style( "jqueryUiCss", 'http://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css', array( '' ), false, 'all' );
	wp_enqueue_script("jqueryUi", 'http://code.jquery.com/ui/1.12.1/jquery-ui.js', array( '' ), false, true );


		wp_enqueue_script( "mine", get_template_directory_uri().'/assets/mine.js', array( 'jquery','jquery-ui-core','jquery-ui-slider' ), 1.0, true );

		wp_localize_script( 'mine', 'mine_obj',
	        array( 
	            'ajaxurl' => admin_url( 'admin-ajax.php' ),
	            'jplayer' => get_template_directory(),
	        )
    	);

		 // Jquery ui core
    	//wp_enqueue_script('jquery-ui-core');



    	wp_enqueue_script('jquery-ui-slider');
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_register_elementor_locations', [ true ], '2.0', 'hello_elementor_register_elementor_locations' );
		if ( apply_filters( 'hello_elementor_register_elementor_locations', $hook_result ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( is_admin() ) {
	require get_template_directory() . '/includes/admin-functions.php';
}

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check hide title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = \Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * Wrapper function to deal with backwards compatibility.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
		} else {
			do_action( 'wp_body_open' );
		}
	}
}


/*$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

if ( ! $wpdb->get_var( $query ) == $table_name ) {
*/   
/*$charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE $table_name (
  ID mediumint(9) NOT NULL AUTO_INCREMENT,
  counters text NOT NULL,
  created_at datetime NOT NULL,
  PRIMARY KEY  (public_key)
) $charset_collate;";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);*/

/*}else{
	echo "Something went wrong";
}*/


global $wpdb;
$table_name = $wpdb->base_prefix.'like_counter';
$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
if ( ! $wpdb->get_var( $query ) == $table_name ) {

	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $table_name (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  counter varchar(200) DEFAULT '' NOT NULL,
	  PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

}

require get_template_directory() . '/includes/like_dislike_calculator.php';
function jplayer_html_code($html){
	$html .='<div id="jp_container_1" class="jp-video jp-video-270p" role="application" aria-label="media player">
	<div class="jp-type-playlist" muted>
		
		<div class="jp-gui">
			<div class="jp-video-play">
				<button class="jp-video-play-icon" role="button" tabindex="0">play</button>
			</div>
			<div class="jp-interface">
				<div class="jp-progress">
					<div class="jp-seek-bar">
						<div class="jp-play-bar"></div>
					</div>
				</div>
				<div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
				<div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
				<div class="jp-controls-holder">
					
					<div class="jp-controls">

						<!--button class="jp-previous" role="button" tabindex="0">previous</button-->
						<!--button class="jp-play" role="button" tabindex="0">play</button-->
						<a href="#" class="cssbutton" id="play"><i class="fa fa-play-circle"></i></a>
						<a href="#" class="cssbutton" id="pause"><i class="fas fa-pause"></i></a>
						<div id="jquery_jplayer_1" class="jp-jplayer"></div>
						
						<div class="wrap_descript">
							<h3 id="jtitle"></h3>
							<h3 id="jartist"></h3>
						</div>

					<div class="wrap_descript2">
						<span>ONLINE</span>
					</div>

					<div class="wrap_descript3">
							<div class="tooltip"><a href="#" class="share" count="1"><i class="fa fa-share-alt" aria-hidden="true"></i></a>

							  <span class="tooltiptext">
							  	<a href="http://www.facebook.com/sharer/sharer.php?u=https://listen.radioking.com/radio/66913/stream/104650" target="_blank"><i class="fa fa-facebook" class="facebook_btn" ></i></a>

							  	<a href="" target="_blank" id="twitter_btn"><i class="fa fa-twitter"></i></a>
							  </span>
							</div>
							<a href="#" class="like_dislike" count="1">
								<i class="fa fa-heart-o" aria-hidden="true"></i>
								<i class="fa fa-heart" aria-hidden="true" style="display:none;"></i>
							</a>'; 
global $wpdb;
$table_name = $wpdb->prefix . "like_counter"; 							
$mylink2 = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = 1", ARRAY_A );
$updated =  $mylink2['counter'];
		$html .= '<span class="counter">'.$updated.'</span> </div>

					<div class="jp-volume-controls">

						<button class="jp-mute" role="button" tabindex="0">mute</button>
						<button class="jp-volume-max" role="button" tabindex="0">max volume</button>
					<div class="jp-volume-bar">
						<div class="jp-volume-bar-value">
						    <span class="jp-volume-bar-knob"></span>
						</div>
					</div>
					</div>
					<div class="jp-toggles">
						
						<button class="jp-repeat" role="button" tabindex="0">repeat</button>
						<button class="jp-shuffle" role="button" tabindex="0">shuffle</button>
						<button class="jp-full-screen" role="button" tabindex="0">full screen</button>
					</div>

						<!--button class="jp-next" role="button" tabindex="0">next</button-->
					</div>
					
				</div>
				<div class="jp-details">
					<div class="jp-title" aria-label="title">&nbsp;</div>
				</div>
			</div>
		</div>
		<div class="jp-playlist">
			<ul>
				<!-- The method Playlist.displayPlaylist() uses this unordered list -->
				<li>&nbsp;</li>
			</ul>
		</div>
		<div class="jp-no-solution">
			<span>Update Required</span>
			To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
		</div>
	</div>
</div>
';

return $html;
}
add_shortcode("MyJplayer", "jplayer_html_code");



