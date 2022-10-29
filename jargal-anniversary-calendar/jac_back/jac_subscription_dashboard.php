<?php
// create custom plugin settings menu
add_action('admin_menu', 'jac_cool_plugin_create_menu');

function jac_cool_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Subscriptions', 'Subscriptions', 'manage_options',"jac_subscriptions", 'jac_cool_plugin_settings_page' , "dashicons-bell", 20 );

	//call register settings function
	add_action( 'admin_init', 'register_jac_cool_plugin_settings' );
}


function register_jac_cool_plugin_settings() {
	//register our settings
	register_setting( 'jac-plugin-settings-group', 'jac_message' );

}

function jac_cool_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff;padding: 15px 20px 15px 20px;">
<h1>Jargal Anniversary Subscriptions</h1><hr>

<?php 
global $wpdb;
$table_name = $wpdb->base_prefix.'jac_subscribe';

$results=$wpdb->get_results( "SELECT * FROM $table_name",OBJECT);

echo '<table id="subscriber_reports" class="display" style="width:100%">
		<thead>
            <tr>
                <th>Email</th>
                <th>Post title</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';
foreach ($results as $result) {
	echo "<tr>";
	echo "<td style='text-align:center;'>".$result->user_email."</td>";
    if(is_numeric($result->post_id)){
	   echo "<td style='text-align:center;'>".get_the_title($result->post_id)."</td>";
    }else{
        echo "<td style='text-align:center;'>".$result->post_id."</td>";
    }
	echo "<td style='text-align:center;'><a href='#' data-id='".$result->id."' class='delete_it' style='background: #ff00009e;padding: 4px 20px;color: #fff;text-decoration: none;border-radius:4px;'> Delete </a></td>";
	echo "</tr>";
}
echo "</tbody>
</table>";


?>

<form method="post" action="options.php">
    <?php settings_fields( 'jac-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'jac-plugin-settings-group' ); ?>
    <br><br><br><br>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Write Email Message</th>
        <td><textarea name="jac_message" cols="10" rows="10" style="width: 100%;" placeholder="Message will be sent as email body."><?php echo esc_attr( get_option('jac_message') ); ?></textarea></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>

</div>



<?php 
/*    $featured_img_url = get_the_post_thumbnail_url(6772,'full'); 
    $message = esc_attr(get_option('jac_message'));
    $m = explode(" ", $message);

    $time_ago = array_search('[time_ago],', $m);
    $m[$time_ago] = esc_html(human_time_diff_floor( get_the_time('U'), current_time('timestamp') ) ); 
	$post_12 = get_post(6772); 
 $message = '<html>
    <head>

        <title>'.get_the_title(6772).'</title>

    </head>
    <body>
        <div id="email_container" style="background: #fff;
    width: 556px;
    margin: auto;
    padding: 0px 17px;border:3px #ee3333 solid;border-bottom:none;">
            <div style="width: 592px;padding: 0 0 0 0px;margin: 15px auto 0px auto;text-align:center;    border-bottom: none;" id="email_header">
                <span><img src="https://tech-time.fr/wp-content/uploads/2020/07/cropped-Tech-Time-2-4.png" style="width:200px;height:60px;padding-top: 20px;">
                </div>
                <p style="text-align: center;padding-bottom: 20px;margin-bottom: 0px;font-size:17px;">'.implode(" ", $m).'</p>
            </div>

            <div style="width:550px; padding:0 20px 20px 20px; background:#fff; margin:0 auto; border:3px #ee3333 solid; border-top:none;color:#454545;line-height:1.5em;border-bottom:0px;" id="email_content">

                <h1 style="padding: 24px 0 0 0;font-family: georgia;font-weight: 500;font-size: 24px;color: #000;border-bottom: 1px solid #bbb;padding-bottom: 13px;margin-top: 0px;">
                    '.get_the_title(6772).'
                </h1>

                <img src="'.$featured_img_url.'" style="width:100%; height:300px;">

                <div style="font-size:17px;line-height:30px; color:#000;">'. $post_12->post_content.'</div>
               
                <p style="">
                    Warm regards,<br>
                    <a href="'.get_site_url().'">'.get_bloginfo("name").'</a>
                </p>

                 <p style=" text-align:center;text-decoration:none;">
                    
        <a href="'.get_site_url().'/?user_email='.$result->user_email.'" style="color:#999;"> Désinscription </a>

                </p>


            
            </div>
        </div>
    </body>
    </html>';
        $headers=array('Content-Type: text/html; charset=UTF-8');
	//if(!in_array($result->user_email, $all_email)){
		$subject = get_the_title(6772);
        wp_mail("aghanizaheer@gmail.com",$subject,$message, $headers);*/

      //  add_post_meta(get_the_ID(),"message_sent",$result->user_email);
    //}



/*$all_email = get_post_meta( get_the_ID(), 'message_sent', false );

print_r($all_email);

$args = array("post_type" => "post", "posts_per_page" => -1);
$query = new WP_Query($args);

if($query->have_posts()): while($query->have_posts()): $query->the_post();

    $post_date =  get_the_date("d-m");
    $today =  date("d-m");

    echo $post_date.'<br>';
    echo $today.'<br>';

endwhile;
endif;*/

 /*delete_post_meta(get_the_ID(),"message_sent");

    $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 

    $post_date =  strtotime(get_the_date("d-m-Y"));
    $today =  strtotime(date("d-m-Y"));
    $status = get_post_meta(get_the_ID(),'rudr_noindex',true);

    global $wpdb;
    $table_name = $wpdb->base_prefix.'jac_subscribe';
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name"));

    $message = esc_attr(get_option('jac_message'));
    $m = explode(" ", $message);

    $time_ago = array_search('[time_ago],', $m);
    $m[$time_ago] = esc_html(human_time_diff_floor( get_the_time('U'), current_time('timestamp') ) ); 

$all_email = get_post_meta( get_the_ID(), 'message_sent', false );
  //  print_r($all_email)."<br>";
foreach ($results as $result) {
 if($post_date == $today && $status != "on"){

 if($result->post_id == "All"){
                        
    $headers=array('Content-Type: text/html; charset=UTF-8');
                        
    $message = '<html>
    <head>

        <title>'.get_the_title().'</title>

    </head>
    <body>
        <div id="email_container" style="background:#444">
            <div style="width:570px; padding:0 0 0 20px; margin:50px auto 12px auto" id="email_header">
                <span style="background:#585858; color:#fff; padding:12px;font-family:trebuchet ms; letter-spacing:1px; 
                    -moz-border-radius-topleft:5px; -webkit-border-top-left-radius:5px; 
                    border-top-left-radius:5px;moz-border-radius-topright:5px; -webkit-border-top-right-radius:5px; 
                    border-top-right-radius:5px;">
                    '.get_bloginfo("name").'
                </div>
            </div>

            <div style="width:550px; padding:0 20px 20px 20px; background:#fff; margin:0 auto; border:3px #000 solid;
                moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; color:#454545;line-height:1.5em; " id="email_content">

                <h1 style="padding:5px 0 0 0; font-family:georgia;font-weight:500;font-size:24px;color:#000;border-bottom:1px solid #bbb">
                    '.get_the_title().'
                </h1>

                <img src="'.$featured_img_url.'" style="width:100%; height:300px;">

                <p>'.implode(" ", $m).'</p>
               
                <p style="">
                    Warm regards,<br>
                    <a href="'.get_site_url().'">'.get_bloginfo("name").'</a>
                </p>

            
            </div>
        </div>
    </body>
    </html>';

	if(!in_array($result->user_email, $all_email)){
		$subject = get_the_title();
        wp_mail( $result->user_email,$subject,$message, $headers);

        add_post_meta(get_the_ID(),"message_sent",$result->user_email);
    }
                   
} 
//echo get_the_ID().'<br>';
//echo $result->post_id.'<br>';
if($result->post_id == get_the_ID()){

//	echo get_the_title()."<br>";
                    
    $headers=array('Content-Type: text/html; charset=UTF-8');
    $message = '<html>
    <head>

    <title>'.get_the_title().'</title>

    </head>
    <body>
        <div id="email_container" style="background:#444">
            <div style="width:570px; padding:0 0 0 20px; margin:50px auto 12px auto" id="email_header">
                <span style="background:#ee3333; color:#000; padding:12px;font-family:trebuchet ms; letter-spacing:1px; 
                    -moz-border-radius-topleft:5px; -webkit-border-top-left-radius:5px; 
                    border-top-left-radius:5px;moz-border-radius-topright:5px; -webkit-border-top-right-radius:5px; 
                    border-top-right-radius:5px;">
                    '.get_bloginfo("name").'
                </div>
            </div>

            <div style="width:550px; padding:0 20px 20px 20px; background:#fff; margin:0 auto; border:3px #000 solid;
                moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; color:#585858;line-height:1.5em; " id="email_content">

                <h1 style="padding:5px 0 0 0; font-family:georgia;font-weight:500;font-size:24px;color:#000;border-bottom:1px solid #bbb">
                    '.get_the_title().'
                </h1>

                <img src="'.$featured_img_url.'" style="width:100%; height:300px;">

                <p>'.implode(" ", $m).'</p>
               
                <p style="">
                    Bonne journée !<br>
                    <a href="'.get_site_url().'">'.get_bloginfo("name").'</a>
                </p>

                 <p style=" text-align:center;text-decoration:none;">
                    
        <a href="'.get_site_url().'/?user_email='.$result->user_email.'" style="color:#999;"> Désinscription </a>

                </p>


            
            </div>
        </div>
    </body>
    </html>';

            $results1 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE post_id='".get_the_ID()."' "));

//            print_r($results1);

            foreach ($results1 as $result1) {
                if($post_date == $today && $status != "on"){
                    $all_email = get_post_meta( get_the_ID(), 'message_sent', false );
	                if(!in_array($result1->user_email, $all_email)){
	                	$subject = get_the_title();
	                    wp_mail( $result1->user_email, $subject ,$message, $headers);
	                    add_post_meta(get_the_ID(),"message_sent",$result1->user_email);
	                }
                }
            }


} //end second if

    }
          
}
   // }

endwhile;
endif;
*/


} ?>