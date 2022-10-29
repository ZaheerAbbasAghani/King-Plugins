<?php 
/*
* Creating a function to create our CPT
*/
 
function custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Job Board', 'Post Type General Name', 'v-job-board' ),
        'singular_name'       => _x( 'Job', 'Post Type Singular Name', 'v-job-board' ),
        'menu_name'           => __( 'Job Board', 'v-job-board' ),
        'parent_item_colon'   => __( 'Parent Job', 'v-job-board' ),
        'all_items'           => __( 'All Job Board', 'v-job-board' ),
        'view_item'           => __( 'View Job', 'v-job-board' ),
        'add_new_item'        => __( 'Add New Job', 'v-job-board' ),
        'add_new'             => __( 'Add New', 'v-job-board' ),
        'edit_item'           => __( 'Edit Job', 'v-job-board' ),
        'update_item'         => __( 'Update Job', 'v-job-board' ),
        'search_items'        => __( 'Search Job', 'v-job-board' ),
        'not_found'           => __( 'Not Found', 'v-job-board' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'v-job-board' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'movies', 'v-job-board' ),
        'description'         => __( 'Job news and reviews', 'v-job-board' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'author','revisions'),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'genres' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => false,
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
         'menu_icon'   => 'dashicons-bell',
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'v_job_board', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type', 0 );


add_action( 'add_meta_boxes_v_job_board', 'meta_box_for_v_job_board' );
function meta_box_for_v_job_board( $post ){
    add_meta_box( 'v_job_board_id', __( 'Additional info', 'v-job-board' ), 'vjobboard_meta_box_html_output', 'v_job_board', 'normal', 'low' );
}

function vjobboard_meta_box_html_output( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'vjobboard_meta_box_nonce' ); //used later for security
    echo '<p><label for="salary_range">'.__('Salary Range', 'v-job-board').'</label> <input type="text" name="salary_range" value="'.get_post_meta($post->ID, 'salary_range', true).'" style="width: 100%;margin: 3px 0px;border: 1px solid #ddd;padding: 1px 10px;"/></p>';

    echo '<p><label for="location">'.__('Location', 'v-job-board').'</label> <input type="text" name="location" value="'.get_post_meta($post->ID, 'location', true).'" style="width: 100%;margin: 3px 0px;border: 1px solid #ddd;padding: 1px 10px;"/></p>';

    echo '<p><label for="Total Jobs Link">'.__('Total Jobs Link', 'v-job-board').'</label> <input type="text" name="total_jobs_link" value="'.get_post_meta($post->ID, 'total_jobs_link', true).'" style="width: 100%;margin: 3px 0px;border: 1px solid #ddd;padding: 1px 10px;"/></p>';

    echo '<p><label for="CV Library Link">'.__('CV Library Link', 'v-job-board').'</label> <input type="text" name="cv_library_link" value="'.get_post_meta($post->ID, 'cv_library_link', true).'" style="width: 100%;margin: 3px 0px;border: 1px solid #ddd;padding: 1px 10px;"/></p>';

    echo '<p><label for="Indeed Link">'.__('Indeed Link', 'v-job-board').'</label> <input type="text" name="indeed_link" value="'.get_post_meta($post->ID, 'indeed_link', true).'" style="width: 100%;margin: 3px 0px;border: 1px solid #ddd;padding: 1px 10px;"/></p>';
	
	 $field_id_value = get_post_meta($post->ID, 'shortlist', true);
	//echo $field_id_value;
	 if($field_id_value == "yes") $field_id_checked = 'checked="checked"'; 
	 echo '<p><label for="Shortlist">'.__('Shortlist', 'v-job-board').' <input type="checkbox" name="shortlist" value="yes" '.$field_id_checked.'/></label></p>';

}

add_action( 'save_post_v_job_board', 'v_job_board_save_meta_boxes_data', 10, 2 );
function v_job_board_save_meta_boxes_data( $post_id ){
    // check for nonce to top xss
    if ( !isset( $_POST['vjobboard_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['vjobboard_meta_box_nonce'], basename( __FILE__ ) ) ){
        return;
    }

    // check for correct user capabilities - stop internal xss from customers
    if ( ! current_user_can( 'edit_post', $post_id ) ){
        return;
    }

    // update fields
    if ( isset( $_REQUEST['salary_range'] ) ) {
        update_post_meta( $post_id, 'salary_range', sanitize_text_field( $_POST['salary_range'] ) );
    }

    if ( isset( $_REQUEST['location'] ) ) {
        update_post_meta( $post_id,'location',sanitize_text_field($_POST['location']));
    }

    if ( isset( $_REQUEST['total_jobs_link'] ) ) {
        update_post_meta( $post_id,'total_jobs_link',sanitize_text_field($_POST['total_jobs_link']));
    }

    if ( isset( $_REQUEST['cv_library_link'] ) ) {
        update_post_meta( $post_id,'cv_library_link',sanitize_text_field($_POST['cv_library_link']));
    }

    if ( isset( $_REQUEST['indeed_link'] ) ) {
        update_post_meta( $post_id,'indeed_link',sanitize_text_field($_POST['indeed_link']));
    }
	if ( isset( $_REQUEST['shortlist'] ) ) {
		update_post_meta($post_id, "shortlist", sanitize_text_field($_POST["shortlist"]));
    }else{
		delete_post_meta($post_id, 'shortlist');
	}
	  
}


// Adding Columns


// Add the custom columns to the v_job_board post type:
add_filter( 'manage_v_job_board_posts_columns', 'set_custom_edit_v_job_board_columns' );
function set_custom_edit_v_job_board_columns($columns) {
    unset( $columns['author'] );
    unset($columns['comments']);
    unset($columns['date']);
    unset($columns['title']);
    $columns['date_posted'] = __( 'Date posted', 'v-job-board' );
    $columns['title'] = __( 'Job Title', 'v-job-board' );
    $columns['salary_range'] = __( 'Salary Range', 'v-job-board' );
    $columns['location'] = __( 'Location', 'v-job-board' );
    $columns['job_description'] = __( 'Job Description', 'v-job-board' );
    $columns['total_jobs_link'] = __( 'Total Jobs Link', 'v-job-board' );
    $columns['cv_library_link'] = __( 'CV Library Link ', 'v-job-board' );
    $columns['indeed_link'] = __( 'Indeed Link', 'v-job-board' );
    return $columns;
}

// Add the data to the custom columns for the v_job_board post type:
add_action( 'manage_v_job_board_posts_custom_column' , 'custom_v_job_board_column', 10, 2 );
function custom_v_job_board_column( $column, $post_id ) {
    switch ( $column ) {

        case 'date_posted' :
            $date = get_the_date('d/m/Y', get_the_ID());
            if ( !empty($date) )
                echo $date;
            else
                _e( 'Not Found!', 'v-job-board' );
            break;

        case 'title' :
            $date = get_the_title(get_the_ID());
            if ( !empty($date) )
                echo $date;
            else
                _e( 'Not Found!', 'v-job-board' );
            break;

        case 'salary_range' :
            $salary_range = get_post_meta( $post_id , 'salary_range' , true ); ;
            if ( !empty($salary_range) )
                echo $salary_range;
            else
                _e( 'Not Found!', 'v-job-board' );
            break;

        case 'location' :
            $location = get_post_meta( $post_id , 'location' , true ); ;
            if ( !empty($location) )
                echo $location;
            else
                _e( 'Not Found!', 'v-job-board' );
            break;

        case 'job_description' :
            $job_description = get_the_content($post_id);
            if ( !empty($job_description) )
                //echo $job_description;
                echo wp_trim_words($job_description, 5, '...');
            else
                _e( 'Not Found!', 'v-job-board' );
            break;

        case 'total_jobs_link' :
            $total_jobs_link = get_post_meta( $post_id , 'total_jobs_link' , true ); ;
            if ( !empty($total_jobs_link) )
                echo "<a href='".$total_jobs_link."'>".$total_jobs_link."</a>";
            else
                _e( 'Not Found!', 'v-job-board' );
            break;

        case 'cv_library_link' :
            $cv_library_link = get_post_meta( $post_id , 'cv_library_link' , true );
            if ( !empty($cv_library_link) )
                echo "<a href='".$cv_library_link."'>".$cv_library_link."</a>";
            else
                _e( 'Not Found!', 'v-job-board' );
            break;

        case 'indeed_link' :
            $indeed_link = get_post_meta($post_id, 'indeed_link' , true );
            if ( !empty($indeed_link) )
                echo "<a href='".$indeed_link."'>".$indeed_link."</a>";
            else
                _e( 'Not Found!', 'v-job-board' );
            break;

  

    }
}


/* 
    Options Page Wordpress
*/


// create custom plugin settings menu
add_action('admin_menu', 'easy_apply_plugin_create_menu');

function easy_apply_plugin_create_menu() {

    //create new top-level menu
    //add_submenu_page('edit.php?post_type=v_job_board', 'Apply Information', 'manage_options', '', 'easy_apply_plugin_settings_page' , plugins_url('/images/icon.png', __FILE__) );

    add_submenu_page(
       'edit.php?post_type=v_job_board',
        __('Apply Information', 'rushhour'),
        __('Apply Information', 'rushhour'),
        'manage_options',
        'ea_apply_information','easy_apply_plugin_settings_page');


}

function easy_apply_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff; padding: 10px 20px;">
<h1>Apply Information</h1><hr>

<i>Use Shortcode in any page to show jobs in frontend: [job-board] </i><br><br>

<table id="apply_information" class="display" style="text-align: center;">
    <thead>
        <tr>
            <th>Name</th>
            <th>Phone no</th>
            <th>Email</th>
            <th>Comments</th>
            <th>Applied for</th>
            <th>CV</th>
            <th>Created</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

<?php 

global $wpdb;
$table_name = $wpdb->base_prefix.'ea_apply';

$query = "SELECT * FROM $table_name ORDER BY created_at ASC";
$results = $wpdb->get_results($query);

$wp_upload_dir =  wp_upload_dir();



foreach ($results as $result) {
    
    echo '<tr>
            <td>'.$result->your_name.'</td>
            <td>'.$result->your_phone.'</td>
            <td>'.$result->your_email.'</td>';
    
    if(!empty($result->additional_comments)){
        echo '<td>'.$result->additional_comments.'</td>';
    }else{
        echo '<td style="color:#ddd;"> Not Provided </td>';
    }
    
    //$images = scandir($wp_upload_dir['basedir'].'/cv/'.$result->id, 1);

    $images = glob($wp_upload_dir['basedir'].'/cv/'.$result->id."/*.{pdf,docx}",GLOB_BRACE);

    echo '<td>'.$result->appliedfor.'</td>';
    
    if(!empty($images[0])){
		$id = strrchr($images[0],"/");
		$id = substr($id,1,strlen($id));
        echo '<td><a href="'.$wp_upload_dir['baseurl'].'/cv/'.$result->id.'/'.$id.'"> Download </a></td>';
    }else{
        echo '<td><a href="#"> Download </a></td>';
    }

    echo '<td>'.$result->created_at.'</td>
          <td><a href="#" data-id="'.$result->id.'" class="delete_record"> Delete </a></td>
         </tr>';
}

?>

    </tbody>
    <tfoot>
        <tr>
            <th>Name</th>
            <th>Phone no</th>
            <th>Email</th>
            <th>Comments</th>
            <th>Applied for</th>
            <th>CV</th>
            <th>Created</th>
            <th>Action</th>
        </tr>
    </tfoot>
</table>


</div>
<?php } ?>