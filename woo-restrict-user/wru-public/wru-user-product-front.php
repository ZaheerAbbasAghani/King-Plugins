<?php

add_action( 'add_meta_boxes', 'so_custom_meta_box' );

function so_custom_meta_box($post){
    add_meta_box('so_meta_box', 'Approve Product', 'custom_element_grid_class_meta_box', $post->post_type, 'normal' , 'high');
}

add_action('save_post', 'so_save_metabox');

function so_save_metabox(){ 
    global $post;
    if(isset($_POST["custom_element_grid_class"])){
         //UPDATE: 
        $meta_element_class = $_POST['custom_element_grid_class'];
        //END OF UPDATE

        update_post_meta($post->ID, 'custom_element_grid_class_meta_box', $meta_element_class);
        //print_r($_POST);
    }
}

function custom_element_grid_class_meta_box($post){
    $meta_element_class = get_post_meta($post->ID, 'custom_element_grid_class_meta_box', true); //true ensures you get just one value instead of an array
    ?>   
    <label>Make product (request a consult):  </label>

    <select name="custom_element_grid_class" id="custom_element_grid_class" style="width: 77%;">
      <option value="no" <?php selected( $meta_element_class, 'no' ); ?>>No</option>
      <option value="yes" <?php selected( $meta_element_class, 'yes' ); ?>>Yes</option>
      
    </select>
    <?php
}
