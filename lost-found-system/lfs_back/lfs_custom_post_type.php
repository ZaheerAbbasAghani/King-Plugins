<?php

function gvw_create_cpt() {
  $labels = array(
    'name'               => _x( 'Advertisements', 'advertisements' ),
    'singular_name'      => _x( 'advertisement', 'advertisements' ),
    'add_new'            => _x( 'Add New', 'advertisements' ),
    'add_new_item'       => __( 'Add New advertisement' ),
    'edit_item'          => __( 'Edit advertisement' ),
    'new_item'           => __( 'New advertisement' ),
    'all_items'          => __( 'All Advertisements' ),
    'view_item'          => __( 'View advertisement' ),
    'search_items'       => __( 'Search Advertisements' ),
    'not_found'          => __( 'No advertisements found' ),
    'not_found_in_trash' => __( 'No advertisements found in the Trash' ), 
    'menu_name'          => __('Advertisements')
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds our advertisements and advertisements specific data',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
    'has_archive'   => true,

  );
  register_post_type( 'advertisement', $args ); 
}


function advertisements_extra_information() {
    add_meta_box( 
        'advertisements_extra_information',
        __( 'advertisement Extra Information', 'advertisements' ),
        array($this,'advertisements_extra_information_content'),
        'advertisement',
        'normal',
        'high'
    );
}


function advertisements_extra_information_content( $post ) {
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_user_email_content_nonce');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_phone_number_content_nonce');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_lost_or_found_date_nonce');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_lost_or_found_place');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_state');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_animal_type');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_animal_breed');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_special_mark');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_birth_Day');
  wp_nonce_field( plugin_basename( __FILE__ ), 'lfs_micro_chip');

  $lfs_user_email = get_post_meta( $post->ID, 'lfs_user_email', true );
  $lfs_phone_number = get_post_meta( $post->ID, 'lfs_phone_number', true );
  $lfs_lost_or_found_date = get_post_meta( $post->ID, 'lfs_lost_or_found_date', true );
  $lfs_lost_or_found_place = get_post_meta( $post->ID, 'lfs_lost_or_found_place', true );
  $lfs_state = get_post_meta( $post->ID, 'lfs_state', true );
  $lfs_animal_type = get_post_meta( $post->ID, 'lfs_animal_type', true );
  $lfs_animal_breed = get_post_meta( $post->ID, 'lfs_animal_breed', true );
  $lfs_special_mark = get_post_meta( $post->ID, 'lfs_special_mark', true );
  $lfs_birth_Day = get_post_meta( $post->ID, 'lfs_birth_Day', true );
  $lfs_micro_chip = get_post_meta( $post->ID, 'lfs_micro_chip', true );

  echo '<label for="lfs_user_email" style="display:block;padding: 4px;margin-bottom: 10px;"> Enter user email';
  echo '<input type="text" id="lfs_user_email" name="lfs_user_email" placeholder="Enter worth to win" value="'.$lfs_user_email.'" style="width:100%;"/></label>';

  echo '<label for="lfs_phone_number" style="display:block;padding: 4px;margin-bottom: 10px;"> Enter phone number';
  echo '<input type="text" id="lfs_phone_number" name="lfs_phone_number" placeholder="Enter advertisement lasting" value="'.$lfs_phone_number.'" style="width:100%;"/></label>';

  echo '<label for="lfs_lost_or_found_date" style="display:block;padding: 4px;margin-bottom: 10px;"> Enter Date';
  echo '<input type="text" id="lfs_lost_or_found_date" name="lfs_lost_or_found_date" placeholder="Enter Date" value="'.$lfs_lost_or_found_date.'" style="width:100%;"/></label>';

  echo '<label for="lfs_lost_or_found_place" style="display:block;padding: 4px;margin-bottom: 10px;"> Enter Place';
  echo '<input type="text" id="lfs_lost_or_found_place" name="lfs_lost_or_found_place" placeholder="Enter Place" value="'.$lfs_lost_or_found_place.'" style="width:100%;"/></label>';

  echo '<label for="lfs_state" style="display:block;padding: 4px;margin-bottom: 10px;"> State';
  echo '<input type="text" id="lfs_state" name="lfs_state" placeholder="Enter Place" value="'.$lfs_state.'" style="width:100%;"/></label>';

  echo '<label for="lfs_animal_type" style="display:block;padding: 4px;margin-bottom: 10px;"> Animal Type';
  echo '<input type="text" id="lfs_animal_type" name="lfs_animal_type" placeholder="Animal Type" value="'.$lfs_animal_type.'" style="width:100%;"/></label>';

  echo '<label for="lfs_animal_breed" style="display:block;padding: 4px;margin-bottom: 10px;"> Animal Breed';
  echo '<input type="text" id="lfs_animal_breed" name="lfs_animal_breed" placeholder="Animal Breed" value="'.$lfs_animal_breed.'" style="width:100%;"/></label>';

  echo '<label for="lfs_special_mark" style="display:block;padding: 4px;margin-bottom: 10px;"> Special Mark';
  echo '<input type="text" id="lfs_special_mark" name="lfs_special_mark" placeholder="Special Mark" value="'.$lfs_special_mark.'" style="width:100%;"/></label>';

  echo '<label for="lfs_birth_Day" style="display:block;padding: 4px;margin-bottom: 10px;"> Birthday';
  echo '<input type="text" id="lfs_birth_Day" name="lfs_birth_Day" placeholder="Birthday" value="'.$lfs_birth_Day.'" style="width:100%;"/></label>';

  echo '<label for="lfs_micro_chip" style="display:block;padding: 4px;margin-bottom: 10px;"> Microchip';
  echo '<input type="text" id="lfs_micro_chip" name="lfs_micro_chip" placeholder="Microchip" value="'.$lfs_micro_chip.'" style="width:100%;"/></label>';

  

}


function gvw_extra_information_box_save( $post_id ) {

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
  return;


  if ( !wp_verify_nonce( $_POST['lfs_user_email_content_nonce'], plugin_basename( __FILE__ ) && !wp_verify_nonce( $_POST['lfs_phone_number_content_nonce'], plugin_basename( __FILE__ ) ) && !wp_verify_nonce( $_POST['lfs_lost_or_found_date_nonce'], plugin_basename( __FILE__ ) ) && !wp_verify_nonce( $_POST['lfs_lost_or_found_place'], plugin_basename( __FILE__ ) ) && !wp_verify_nonce( $_POST['lfs_state'], plugin_basename( __FILE__ ) ) && !wp_verify_nonce( $_POST['lfs_animal_type'], plugin_basename( __FILE__ ) ) && !wp_verify_nonce( $_POST['lfs_animal_breed'], plugin_basename( __FILE__ ) ) && !wp_verify_nonce( $_POST['lfs_special_mark'], plugin_basename( __FILE__ ) ) && !wp_verify_nonce( $_POST['lfs_birth_Day'], plugin_basename( __FILE__ ) ) && !wp_verify_nonce( $_POST['lfs_micro_chip'], plugin_basename( __FILE__ ) ) ) )    
  return;

  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
    return;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
    return;
  }


  $lfs_user_email = $_POST['lfs_user_email'];
  update_post_meta( $post_id, 'lfs_user_email', $lfs_user_email );

  $lfs_phone_number = $_POST['lfs_phone_number'];
  update_post_meta( $post_id, 'lfs_phone_number', $lfs_phone_number );

  $lfs_lost_or_found_date = $_POST['lfs_lost_or_found_date'];
  update_post_meta( $post_id, 'lfs_lost_or_found_date', $lfs_lost_or_found_date );

  $lfs_lost_or_found_place = $_POST['lfs_lost_or_found_place'];
  update_post_meta( $post_id, 'lfs_lost_or_found_place', $lfs_lost_or_found_place );

  $lfs_state = $_POST['lfs_state'];
  update_post_meta( $post_id, 'lfs_state', $lfs_state);

  $lfs_animal_type = $_POST['lfs_animal_type'];
  update_post_meta( $post_id, 'lfs_animal_type', $lfs_animal_type);

  $lfs_animal_breed = $_POST['lfs_animal_breed'];
  update_post_meta( $post_id, 'lfs_animal_breed', $lfs_animal_breed);

  $lfs_special_mark = $_POST['lfs_special_mark'];
  update_post_meta( $post_id, 'lfs_special_mark', $lfs_special_mark);

  $lfs_birth_Day = $_POST['lfs_birth_Day'];
  update_post_meta( $post_id, 'lfs_birth_Day', $lfs_birth_Day);

  $lfs_micro_chip = $_POST['lfs_micro_chip'];
  update_post_meta( $post_id, 'lfs_micro_chip', $lfs_micro_chip);

}

/*function gvw_show_advertisement_extra_information( $content ) {
    if ( is_single()) {
        
  $lfs_user_email = get_post_meta( $post->ID, 'lfs_user_email', true );
  $lfs_phone_number = get_post_meta( $post->ID, 'lfs_phone_number', true );
  $lfs_lost_or_found_date = get_post_meta( $post->ID, 'lfs_lost_or_found_date', true );
  $lfs_lost_or_found_place = get_post_meta( $post->ID, 'lfs_lost_or_found_place', true );
  $lfs_state = get_post_meta( $post->ID, 'lfs_state', true );
  $lfs_animal_type = get_post_meta( $post->ID, 'lfs_animal_type', true );
  $lfs_animal_breed = get_post_meta( $post->ID, 'lfs_animal_breed', true );
  $lfs_special_mark = get_post_meta( $post->ID, 'lfs_special_mark', true );
  $lfs_birth_Day = get_post_meta( $post->ID, 'lfs_birth_Day', true );
  $lfs_micro_chip = get_post_meta( $post->ID, 'lfs_micro_chip', true );

        
  $content .=  "<p>Worth to win: <b> $lfs_user_email </b></p>";
  $content .= "<p>Days advertisement Lasting: <b> $lfs_phone_number </b></p>";
        return $content;
    } 
    return $content;
}

*/