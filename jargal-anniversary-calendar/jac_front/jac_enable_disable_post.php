<?php 

function rudr_add_a_metabox() {
    add_meta_box(
        'rudr_metabox', // metabox ID, it also will be the HTML id attribute
        'Enable/Disable Anniversary', // title
        'rudr_display_metabox', // this is a callback function, which will print HTML of our metabox
        'post', // post type or post types in array
        'normal', // position on the screen where metabox should be displayed (normal, side, advanced)
        'default' // priority over another metaboxes on this page (default, low, high, core)
    );
}
 
add_action( 'add_meta_boxes', 'rudr_add_a_metabox' );

function rudr_display_metabox( $post ) {
    /*
     * needed for security reasons
     */
    wp_nonce_field( basename( __FILE__ ), 'rudr_metabox_nonce' );
    /*
     * text field
     */
    //$html = '<p><label>SEO title <input type="text" name="rudr_title" value="' . esc_attr( get_post_meta($post->ID, 'rudr_title',true) )  . '" /></label></p>';
    /*
     * checkbox
     */
    $html .= '<p><label><input type="checkbox" name="rudr_noindex" ';
    $html .= checked( get_post_meta($post->ID, 'rudr_noindex',true), 'on', false );
    $html .= ' /> Check this to hide post from anniversairy calendar.</label> <a href="#" class="button button-primary send_email" style="float:right;" post_id="'.$post->ID.'"> Send Test Email </a></p>';
    /*
     * print all of this
     */
    echo $html;
}

function rudr_save_post_meta( $post_id, $post ) {
    /* 
     * Security checks
     */
    if ( !isset( $_POST['rudr_metabox_nonce'] ) 
    || !wp_verify_nonce( $_POST['rudr_metabox_nonce'], basename( __FILE__ ) ) )
        return $post_id;
    /* 
     * Check current user permissions
     */
    $post_type = get_post_type_object( $post->post_type );
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
        return $post_id;
    /*
     * Do not save the data if autosave
     */
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return $post_id;
 
    if ($post->post_type == 'post') { // define your own post type here
        //update_post_meta($post_id, 'rudr_title', sanitize_text_field( $_POST['rudr_title'] ) );
        update_post_meta($post_id, 'rudr_noindex', $_POST['rudr_noindex']);
    }
    return $post_id;
}
 
add_action( 'save_post', 'rudr_save_post_meta', 10, 2 );


