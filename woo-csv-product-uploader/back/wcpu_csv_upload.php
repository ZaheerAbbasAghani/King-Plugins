<?php 

add_action( 'wp_ajax_wcpu_csv_upload', 'wcpu_csv_upload' );
add_action( 'wp_ajax_nopriv_wcpu_csv_upload', 'wcpu_csv_upload' );

function wcpu_csv_upload() {
    global $wpdb; // this is how you get access to the database

    //print_r($_FILES);

    if(!empty($_FILES["file"]["name"]))
{
 
    // Allowed mime types
    $fileMimes = array(
        'text/x-comma-separated-values',
        'text/comma-separated-values',
        'application/octet-stream',
        'application/vnd.ms-excel',
        'application/x-csv',
        'text/x-csv',
        'text/csv',
        'application/csv',
        'application/excel',
        'application/vnd.msexcel',
        'text/plain'
    );
 
    // Validate whether selected file is a CSV file
    if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes))
    {
    
    $all_products = array();

    $all_ids = get_posts( array(
        'post_type' => 'product',
        'numberposts' => -1,
        'post_status' => 'publish',
        'fields' => 'ids',
    ));
    foreach ( $all_ids as $id ) {
        array_push($all_products,  $id);
    }


            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
 
            // Skip the first line
            fgetcsv($csvFile);
 
            // Parse data from CSV file line by line
            while (($value = fgetcsv($csvFile, 10000, ",")) !== FALSE)
            {


            $BothCategories = explode(">", $value[0]);

            $check_post = get_page_by_title($value[2], OBJECT, 'product');
            if($check_post){
                //echo "Exists <br>";
                array_push($available_products, $check_post->ID);

                $post = [

                    'post_author' => get_current_user_id(),
                    'post_title' => wp_strip_all_tags($value[2]),
                    'post_name' => $value[2],
                    'post_excerpt' => $value[3],
                    'post_content' => $value[4],
                    'post_status' => "publish",
                    'post_type' => "product",
                ];

                //Create Post

                //$product_id = wp_insert_post($post);

                $product_id = wp_update_post($post);

                update_post_meta($product_id, '_stock_status', 'instock');
                update_post_meta($product_id, '_stock', $value[7]);
                update_post_meta($product_id, '_price', $value[5]);
                update_post_meta( $product_id, '_regular_price', $value[5] );
                update_post_meta( $product_id, '_sku', $value[1] );



            }else{
                //echo "Not Exists <br>";

              //  echo $BothCategories[0]


                $post = [

                    'post_author' => get_current_user_id(),
                    'post_title' => wp_strip_all_tags($value[2]),
                    'post_name' => $value[2],
                    'post_excerpt' => $value[3],
                    'post_content' => $value[4],
                    'post_status' => "publish",
                    'post_type' => "product",
                ];

                //Create Post

                $product_id = wp_insert_post($post);

                // Creating Category

                $cat_name = utf8_encode($BothCategories[0]); // category name we want to assign the post to 

                $taxonomy = 'product_cat'; // category by default for posts for other custom post types like woo-commerce it is product_cat

                $append = true ;// true means it will add the cateogry beside already set categories. false will overwrite

                //get the category to check if exists

                $cat  = get_term_by('name', $cat_name , $taxonomy);
                //check existence

                if($cat == false){

                    //cateogry not exist create it 
                    $cat = wp_insert_term($cat_name, $taxonomy);
                    $child_cat = wp_insert_term(utf8_encode($BothCategories[1]), 'product_cat', array('description'=> '', 'slug' => sanitize_title(utf8_encode($BothCategories[1])),'parent' => $cat['term_id']));

                    $cid = $child_cat['term_id'];

                }else{

                   $cid = $BothCategories[1];
                }



                //setting post category 

                wp_set_object_terms($product_id, array($cid), 'product_cat');
                wp_set_object_terms($product_id, array($value[6]), 'product_tag');

                update_post_meta($product_id, '_stock_status', 'instock');
                update_post_meta($product_id, '_stock', $value[7]);
                update_post_meta($product_id, '_price', $value[5]);
                update_post_meta( $product_id, '_regular_price', $value[5] );
                update_post_meta( $product_id, '_sku', $value[1] );


                // only need these if performing outside of admin environment

                require_once(ABSPATH . 'wp-admin/includes/media.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                // example image
                $image = $value[8];
                // magic sideload image returns an HTML image, not an ID
                $media = media_sideload_image($image, $product_id);

                // therefore we must find it so we can set it as featured ID

                if(!empty($media) && !is_wp_error($media)){

                    $args = array(
                        'post_type' => 'attachment',
                        'posts_per_page' => -1,
                        'post_status' => 'any',
                        'post_parent' => $product_id
                    );

                    // reference new image to set as featured

                    $attachments = get_posts($args);

                    if(isset($attachments) && is_array($attachments)){

                        foreach($attachments as $attachment){

                            // grab source of full size images (so no 300x150 nonsense in path)

                            $image = wp_get_attachment_image_src($attachment->ID, 'full');

                            // determine if in the $media image we created, the string of the URL exists

                            if(strpos($media, $image[0]) !== false){

                                // if so, we found our image. set it as thumbnail

                                set_post_thumbnail($product_id, $attachment->ID);

                                // only want one image

                                break;
                            }
                        }
                    }
                }


            } // if not exists


     
            }
 
            // Close opened CSV file
            fclose($csvFile);


            
            $need_to_delete = array_diff($all_products, $available_products);

            foreach ($need_to_delete as $delete) {
                wp_delete_post($delete, true);

                delete_post_meta( $delete, '_stock_status', get_post_meta( $delete, '_stock_status', true ));

                delete_post_meta( $delete, '_stock', get_post_meta($delete,'_stock', true ));
                delete_post_meta( $delete, '_price', get_post_meta($delete,'_price', true ));
                delete_post_meta( $delete, '_regular_price', get_post_meta($delete,'_regular_price', true ));
                delete_post_meta( $delete, '_sku', get_post_meta($delete,'_sku', true ));

            }

 
            echo "Success";
         
    }
    else
    {
        echo "Error1";
    }
}else{
  echo "Error2";  
}



    wp_die(); // this is required to terminate immediately and return a proper response
}