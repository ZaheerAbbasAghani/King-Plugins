<?php 

add_action( 'wp_ajax_nopriv_ea_delete_appliedfor', 'ea_delete_appliedfor' );
add_action( 'wp_ajax_ea_delete_appliedfor', 'ea_delete_appliedfor' );
function ea_delete_appliedfor() {

	global $wpdb;
    $table_name = $wpdb->base_prefix.'ea_apply';
    $id = $_POST['id'];
    $info = $wpdb->delete($table_name,array('id'=>$id),array('%d'));
    
    if($info == 1){

        $wp_upload_dir =  wp_upload_dir();
        $lastid = $wpdb->insert_id;
        $custom_upload_folder= $wp_upload_dir['basedir'].'/cv/'.$id;

        array_map('unlink', glob("$custom_upload_folder/*.*"));
        rmdir($custom_upload_folder);

    	echo  "Deleted Successfully.";
	}else{
    	echo "Something went wrong!";
    }


	wp_die();
}