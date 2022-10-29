<?php


function wru_approve_product_for_get_started() {
   add_meta_box(
       'approve_product_for_get_started',       // $id
       'Click to approve',                  // $title
       'wru_approve_product_for_get_started_process',  // $callback
       'product',                 // $page
       'normal',                  // $context
       'high'                     // $priority
   );
}
add_action('add_meta_boxes', 'wru_approve_product_for_get_started');

//showing custom form fields
function wru_approve_product_for_get_started_process() {


 global $post;
  $custom = get_post_custom($post->ID);
  $field_id = $custom["field_id"][0];
 ?>

  <label>Request a Consult</label>
  <?php $field_id_value = get_post_meta($post->ID, 'field_id', true);
  if($field_id_value == "yes") $field_id_checked = 'checked="checked"'; ?>
    <input type="checkbox" name="field_id" id="request_consult" value="yes" <?php echo $field_id_checked; ?> />
  <?php

}

// Save Meta Details
add_action('save_post', 'save_details');

function save_details(){
  global $post;

if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post->ID;
}

  update_post_meta($post->ID, "field_id", $_POST["field_id"]);
}