<?php
// create custom plugin settings menu
add_action('admin_menu', 'gf_plugin_create_menu');

function gf_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Gift Finder', 'Gift Finder', 'manage_options', 'gf_gift_finder', 'gf_plugin_settings_page', 'dashicons-search', 20 );

	//call register settings function
	add_action( 'admin_init', 'register_gf_plugin_settings' );
}


function register_gf_plugin_settings() {
	//register our settings
	/*register_setting( 'my-cool-plugin-settings-group', 'new_option_name' );
	register_setting( 'my-cool-plugin-settings-group', 'some_other_option' );
	register_setting( 'my-cool-plugin-settings-group', 'option_etc' );*/
}

function gf_plugin_settings_page() {
?>
<style type="text/css">
    
    #build-wrap ul.stage-wrap li{
        border: 1px solid #ddd;
        padding: 14px;
        box-shadow: 1px 1px 4px #ddd;
    }


</style>
<div class="wrap" style="background: #fff; padding: 10px 20px;height: auto;overflow: hidden;">
<h1> Gift Finder Form</h1> <hr>


<div class="gf_left_side" style="width:70%; float: left;margin-left: 5px;padding: 10px;">
    <div id="build-wrap">
    	<img src="https://www.geekpassionsgifts.com/wp-content/uploads/2022/05/Ajux_loader.gif" class="loadingImg" style="width:300px;margin:auto;">
    </div>
</div>

<div class="gf_right_side" style="width: 25%; float: right; margin-left: 5px;background: #eee;padding: 10px;box-shadow: 2px 2px 2px #ddd, -2px -2px 2px #ddd;">


    <h3>Woocommerce Tags</h3> <hr>

    <?php 

        $terms = get_terms( 'product_tag' );
        $term_array = array();
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
            echo "<ul class='wooTags'>";
            foreach ( $terms as $term ) {
                echo "<li style='display:inline-block;background: #fff;border: 1px solid #ddd;padding: 4px 13px;border-radius: 20em;margin: 0px 5px 5px 0px;'>". $term->name. "</li>";
            }
            echo "</ul>";
        }

    ?>


</div>


</div>
<?php } ?>