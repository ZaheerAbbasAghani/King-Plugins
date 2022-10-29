<?php 

require_once plugin_dir_path(__FILE__).'vendor/autoload.php';
use Twilio\Rest\Client;

/*
* Creating a function to create our CPT
*/
 
function camp_custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Campaigns', 'Post Type General Name', 'campaign-manager' ),
        'singular_name'       => _x( 'Campaign', 'Post Type Singular Name', 'campaign-manager' ),
        'menu_name'           => __( 'Campaigns', 'campaign-manager' ),
        'parent_item_colon'   => __( 'Parent Campaign', 'campaign-manager' ),
        'all_items'           => __( 'All Campaigns', 'campaign-manager' ),
        'view_item'           => __( 'View Campaign', 'campaign-manager' ),
        'add_new_item'        => __( 'Add New Campaign', 'campaign-manager' ),
        'add_new'             => __( 'Add New', 'campaign-manager' ),
        'edit_item'           => __( 'Edit Campaign', 'campaign-manager' ),
        'update_item'         => __( 'Update Campaign', 'campaign-manager' ),
        'search_items'        => __( 'Search Campaign', 'campaign-manager' ),
        'not_found'           => __( 'Not Found', 'campaign-manager' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'campaign-manager' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'campaigns', 'campaign-manager' ),
        'description'         => __( 'Campaign news and reviews', 'campaign-manager' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array('title','editor','author','thumbnail','comments', 'revisions' ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'genres' ),
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
        'menu_position'       => 25,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-megaphone',
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'campaigns', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'camp_custom_post_type', 0 );


function camp_campaign_namager_metaboxes( ) {
   global $wp_meta_boxes;
   add_meta_box('campaigns_box', __('Campaign Information'), 'camp_campaigns_namager_metaboxes_html', 'campaigns', 'normal', 'high');
}
add_action( 'add_meta_boxes_campaigns', 'camp_campaign_namager_metaboxes' );


function camp_campaigns_namager_metaboxes_html()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $start_date = isset($custom["start_date"][0])?$custom["start_date"][0]:'';
    $end_date = isset($custom["end_date"][0])?$custom["end_date"][0]:'';
    $candidates = isset($custom["candidates"][0])?$custom["candidates"][0]:'';
?>
    <label style="font-size:14px;margin-bottom:4px;display:block;">Start Date:</label>
    <input type="datetime-local" name="start_date" id="start_date" value="<?php echo $start_date; ?>" style="width:100%;padding: 4px;border: 1px solid #ddd;margin-bottom: 10px;">

    <label style="font-size:14px;margin-bottom:4px;display:block;">End Date:</label>
    <input type="datetime-local" name="end_date" id="end_date" value="<?php echo $end_date; ?>" style="width:100%;padding: 4px;border: 1px solid #ddd;margin-bottom: 10px;">

    <label style="font-size:14px;margin-bottom:4px;display:block;">Candidates:</label>
    <input type="text" name="candidates" id="candidates" value="<?php echo $candidates; ?>" style="width:100%;padding: 4px;border: 1px solid #ddd;margin-bottom: 10px;">

<table class="table table-bordered" id="dynamic_field" style="width: 100%;" data-id="<?php echo $post->ID; ?>">
    <tr>
        <td>
            <label style="font-size:14px;margin-bottom:4px;display:block;">Voters:</label>
            
            <div class="field-wrap">
              <!--   <input type="text" name="camp_voter_name[]"  placeholder="Enter Voter Name" style="width:49%;" /> -->
                <input type="text" name="camp_voter_phone[]" placeholder="Enter Voter Phone" id="camp_voter_phone_0" class="camp_voter_phone"  style="width:88%;"/><button type="button" name="add" id="add" class="button button-primary" style="margin-top: 0px;margin-left: 5px;">Add More</button>
            </div>
        </td>
    </tr>
</table>

<br>
<table border="1" cellpadding="15" style="width:100%;text-align: center;">
<?php 

//$voter_name =   unserialize(get_post_meta($post->ID, 'camp_voter_name', true));
$voter_phone =  unserialize(get_post_meta($post->ID, 'camp_voter_phone', true));
$domain_name=$_SERVER['HTTP_HOST'].dirname(dirname(dirname($_SERVER['PHP_SELF'])));

if(!empty($voter_phone)){
    $i=0;
    echo "<tr><td>Voter Name </td> <td>Voter Email </td> <td> Action </td> </tr>";
    foreach ($voter_phone as $key => $value) {
        echo "<tr data-id='".$post->ID."'><td class='username'>".$value."</td><td class='userphone'>".$value."@".rtrim($domain_name,"/")."</td><td><a href='#' class='delete_row button button-default'>  Delete </a></td> </tr>";
        $i++;
    }
}

?>
    
</table>

<?php

}

function camp_campaigns_namager_save_post()
{
    if(empty($_POST)) return; //why is camp_campaigns_namager_save_post triggered by add new? 
    global $post;

    $domain_name=$_SERVER['HTTP_HOST'].dirname(dirname(dirname($_SERVER['PHP_SELF'])));
    $site_url = get_site_url();
    $sid = get_option('camp_account_sid');
    $token = get_option('camp_auth_token');
    //echo $sid;
    $twilio = new Client($sid, $token);

    if(!empty($_POST['camp_voter_phone'][0])){
        if(empty(get_post_meta($post->ID, 'camp_voter_phone', true ))){
            update_post_meta($post->ID,"camp_voter_phone",serialize($_POST['camp_voter_phone']));
        }else{
            $old_data =unserialize(get_post_meta($post->ID, 'camp_voter_phone', true));
            $new_data = $_POST['camp_voter_phone'];
            $final = array_merge($old_data, $new_data);
            update_post_meta($post->ID,"camp_voter_phone",serialize($final));
        }
    }

    update_post_meta($post->ID, "start_date", $_POST["start_date"]);
    update_post_meta($post->ID, "end_date", $_POST["end_date"]);
    update_post_meta($post->ID, "candidates", $_POST["candidates"]);

    $voter_phone =  unserialize(get_post_meta($post->ID, 'camp_voter_phone', true));

    $j=0;
    foreach ($voter_phone as $key => $value) {
       
        $exists = email_exists( $value."@".rtrim($domain_name,"/"));
   
        if ( !$exists ) {

            $characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < 10; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            $user_id = wp_create_user($value, $randomString, $value."@".rtrim($domain_name,"/"));
            add_user_meta( $user_id, "votable", $post->ID);
            $string = str_replace('+', '', $value);

            $loginUrl= $site_url.'/?cusername='.$string.'&cpassword='.$randomString.'&postid='.$post->ID;

            $message = $twilio->messages
            ->create($value, // to
                   ["body" => "Your account created at: ".get_bloginfo('name')." \n\nYour account access is \nLogin Url: $loginUrl \nUsername: $string \nPassword: $randomString ", "from" => get_option('camp_phone_number') ]
            );

            $j++;
        }else{


            $userList = $_POST['camp_voter_phone'];


            if(!empty($userList[0])){

                foreach ($userList as $users) {
                    $lg = get_user_by("email", $users."@".rtrim($domain_name,"/"));

                    $votable = get_user_meta($lg->ID, "votable", false );

                    if(!in_array($post->ID, $votable)) {
                       
                        update_user_meta($lg->ID, "votable", $post->ID);

                        $string = str_replace('+', '', $users);
                        $loginUrl= get_the_permalink($post->ID)."/?username=$string&logit=1";

                        $message = $twilio->messages
                        ->create($users, // to
                        ["body" => "Hi! \n\nYou registered for campaign: ".get_the_title($post->ID)." \n\nFollow Below Url:\n\n $loginUrl ", "from" => get_option('camp_phone_number') ]
                        );
                        
                    }

                }
            }

        }
    }
}   

add_action( 'save_post_campaigns', 'camp_campaigns_namager_save_post' );   