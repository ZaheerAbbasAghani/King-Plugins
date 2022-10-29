<?php
add_action( 'add_meta_boxes', 'add_meta_box_process' ) ;
	

function add_meta_box_process( $post_type ) {
    $post_types = array('product');     //limit meta box to certain post types
    global $post;
    $product = get_product( $post->ID );
    if ( in_array( $post_type, $post_types )) {
        add_meta_box(
            'wf_child_letters'
            ,__( 'Text Replacemenet Values', 'woocommerce' )
            ,'render_meta_box_content'
            ,$post_type
            ,'advanced'
            ,'high'
        );
    }
}
function render_meta_box_content() {
    global $post;
    $gpminvoice_group = get_post_meta($post->ID, 'customdata_group', true);
    //print_r($gpminvoice_group);
     wp_nonce_field( 'gpm_repeatable_meta_box_nonce', 'gpm_repeatable_meta_box_nonce' );
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function( $ ){
        $( '#add-row1' ).on('click', function() {
            var row = $( '.empty-row1.screen-reader-text' ).clone(true);
            row.removeClass( 'empty-row1 screen-reader-text' );
            row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
            return false;
        });

        $( '.remove-row' ).on('click', function() {
            $(this).parents('tr').remove();
            return false;
        });
    });
  </script>
  <table id="repeatable-fieldset-one" width="100%">
  <tbody>
    <?php
     if ( $gpminvoice_group ) :
      foreach ( $gpminvoice_group as $field ) {
    ?>
    <tr>
      <td width="100%">
        <input type="text"  placeholder="Enter text placement" name="TitleItem[]" value="<?php if($field['TitleItem'] != '') echo esc_attr( $field['TitleItem'] ); ?>" style="width:100%;"/></td> 
      <td width="100%"><a class="button remove-row" href="#1">Remove</a></td>
    </tr>
    <?php
    }
    else :
    // show a blank one
    ?>
    <tr>
      <td> 
        <input type="text" placeholder="Enter text placement" title="Title" name="TitleItem[]" style="width:100%;" /></td>
   
      <td><a class="button  cmb-remove-row-button button-disabled" href="#">Remove</a></td>
    </tr>
    <?php endif; ?>

    <!-- empty hidden one for jQuery -->
    <tr class="empty-row1 screen-reader-text">
      <td>
        <input type="text" placeholder="Enter text placement" name="TitleItem[]"/></td>
      	
      <td><a class="button remove-row" href="#">Remove</a></td>
    </tr>
  </tbody>
</table>
<p><a id="add-row1" class="button" href="#">Add another</a></p>
 <?php
}
add_action('save_post', 'custom_repeatable_meta_box_save');
function custom_repeatable_meta_box_save($post_id) {
    if ( ! isset( $_POST['gpm_repeatable_meta_box_nonce'] ) ||
    ! wp_verify_nonce( $_POST['gpm_repeatable_meta_box_nonce'], 'gpm_repeatable_meta_box_nonce' ) )
        return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!current_user_can('edit_post', $post_id))
        return;

    $old = get_post_meta($post_id, 'customdata_group', true);
    $new = array();
    $invoiceItems = $_POST['TitleItem'];
    //$prices = $_POST['TitleDescription'];
     $count = count( $invoiceItems );
     for ( $i = 0; $i < $count; $i++ ) {
        if ( $invoiceItems[$i] != '' ) :
            $new[$i]['TitleItem'] = stripslashes( strip_tags( $invoiceItems[$i] ) );
      //      $new[$i]['TitleDescription'] = stripslashes( $prices[$i] ); // and however you want to sanitize
        endif;
    }
    if ( !empty( $new ) && $new != $old )
        update_post_meta( $post_id, 'customdata_group', $new );
    elseif ( empty($new) && $old )
        delete_post_meta( $post_id, 'customdata_group', $old );


}
