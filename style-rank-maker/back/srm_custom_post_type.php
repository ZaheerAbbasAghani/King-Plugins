<?php 

/*
* Creating a function to create our CPT
*/
 
function srm_custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Styles','Post Type General Name', 'srm' ),
        'singular_name'       => _x( 'Style','Post Type Singular Name', 'srm' ),
        'menu_name'           => __( 'Styles', 'srm' ),
        'parent_item_colon'   => __( 'Parent Style', 'srm' ),
        'all_items'           => __( 'All Styles', 'srm' ),
        'view_item'           => __( 'View Style', 'srm' ),
        'add_new_item'        => __( 'Add New Style', 'srm' ),
        'add_new'             => __( 'Add New', 'srm' ),
        'edit_item'           => __( 'Edit Style', 'srm' ),
        'update_item'         => __( 'Update Style', 'srm' ),
        'search_items'        => __( 'Search Style', 'srm' ),
        'not_found'           => __( 'Not Found', 'srm' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'srm' ),
        'featured_image'      => __( 'Upload Images','srm'),
        'set_featured_image'    => __( 'Add Image', 'srm' ),
        'remove_featured_image' => _x( 'Remove Image', 'srm' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'Styles', 'srm' ),
        'description'         => __( 'Style news and reviews', 'srm' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title','author', 'thumbnail','excerpt'),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'type' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
         'menu_icon'   => 'dashicons-admin-appearance',
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'styles', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'srm_custom_post_type', 0 );



add_action( 'pre_get_posts', 'srm_add_my_post_types_to_query' );
 
function srm_add_my_post_types_to_query( $query ) {
    if ( is_home() && $query->is_main_query() )
        $query->set( 'post_type', array('post', 'styles'));
    return $query;
}


add_action( 'init', 'srm_create_discog_taxonomies', 0 );

function srm_create_discog_taxonomies()
{
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name' => _x( 'Types', 'taxonomy general name' ),
    'singular_name' => _x( 'Type', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Types' ),
    'popular_items' => __( 'Popular Types' ),
    'all_items' => __( 'All Types' ),
    'parent_item' => __( 'Parent Type' ),
    'parent_item_colon' => __( 'Parent Type:' ),
    'edit_item' => __( 'Edit Type' ),
    'update_item' => __( 'Update Type' ),
    'add_new_item' => __( 'Add New Type' ),
    'new_item_name' => __( 'New Type Name' ),
  );
  register_taxonomy('types',array('styles'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_in_rest' => true,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'types' ),
  ));
}

add_action( 'admin_menu', 'books_register_ref_page' );
/**
* Adds a submenu page under a custom post type parent.
*/
function books_register_ref_page() {
    add_submenu_page(
        'edit.php?post_type=styles',
        __( 'Styles Settings', 'srm' ),
        __( 'Settings', 'srm' ),
        'manage_options',
        'styles-settings',
        'styles_settings_page_callback'
    );

    //call register settings function
    add_action( 'admin_init', 'register_srm_plugin_settings' );


}


function register_srm_plugin_settings() {
    //register our settings
    register_setting( 'srm-plugin-settings-group', 'upload_image' );
    register_setting( 'srm-plugin-settings-group', 'description' );
    register_setting( 'srm-plugin-settings-group', 'button_text' );
}


/**
* Display callback for the submenu page.
*/
function styles_settings_page_callback(){
?>
<div class="wrap" style="padding: 10px 20px; background: #fff;">
<h1>Styles Settings </h1> <hr>
<?php settings_errors(); ?>
<form method="post" action="options.php">
    <?php settings_fields( 'srm-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'srm-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Upload Image</th>
        <td><img class="upload_image" src="<?php echo get_option('upload_image'); ?>" height="100" width="100"/>
                <input class="upload_image_url" type="text" name="upload_image" size="60" value="<?php echo get_option('upload_image'); ?>">
                <a href="#" class="upload_image_upload">Upload</a>

        </td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Description</th>
            <td>
                <?php 

                $content = get_option('description');
                wp_editor( $content, 'description', $settings = array('textarea_rows'=> '10') );

                ?>
            </td>
        </tr>

        <tr valign="top">
        <th scope="row">Button Text</th>
            <td>
                <input class="btnText" type="text" name="button_text" size="60" value="<?php echo get_option('button_text'); ?>">
            </td>
        </tr>
    
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>