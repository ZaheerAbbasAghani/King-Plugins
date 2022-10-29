<?php
add_action( 'wp_ajax_jac_send_test_email', 'jac_send_test_email' );
add_action( 'wp_ajax_nopriv_jac_send_test_email', 'jac_send_test_email' );

function jac_send_test_email() {
    global $wpdb;
    
$id = $_POST['id'];

$args = array("post_type" => "post", "p" => $id);
$query = new WP_Query($args);

if($query->have_posts()): while($query->have_posts()): $query->the_post();

$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 
$message = esc_attr(get_option('jac_message'));
$m = explode(" ", $message);

$time_ago = array_search('[time_ago]', $m);
$m[$time_ago] = esc_html(human_time_diff_floor( get_the_time('U'), current_time('timestamp') ) ); 

//$post = get_post($id); 

 $headers=array('Content-Type: text/html; charset=UTF-8');
   $message = '<html>
    <head>

        <title>'.get_the_title().'</title>

    </head>
    <body>
        <div id="email_container" style="background: #fff;
    width: 556px;
    margin: auto;
    padding: 0px 17px;border:3px #ee3333 solid;">
         
                 <div style="width:100%;display:block;text-align:center;">
                 <img src="https://tech-time.fr/wp-content/uploads/2020/12/Tech-Time-logo-2-e1607210236183.png" style="width:90%;padding-top: 10px;">
                </div>
            
                <div style="text-align: center;padding-top: 10px;margin-bottom: 30px;font-size:17px;">'.implode(" ", $m).'</div>
          
            	<a href="'.get_the_permalink().'" style="background: #ee3333;padding: 13px 20px;color: #fff;text-decoration: none;font-weight: bold;font-size:  x-small;">'.get_the_date("j F Y").'</a>
                <a href="'.get_the_permalink().'" style="text-decoration: none;" target="_blank"><h1 style="padding: 15px 0 0 0;font-family: Gudea, georgia;font-weight: 500;font-size: 24px;color: #000;border-bottom: 1px solid #bbb;padding-bottom: 0px;margin-top: 0px;">'.get_the_title().'</span> 
                </h1></a>

                <img src="'.$featured_img_url.'" style="width:100%;">

                <div style="font-size:17px;line-height:30px; color:#000;">'. wp_trim_words( get_the_content(),100, "" ) .' <a href="'.get_the_permalink().'" style="color: #f10909;" target="_blank">...Lire la suite >> </a></div>
               
                <p style="">
                    <a href="'.get_site_url().'">'.get_bloginfo("name").'</a>
                </p>
      
            </div>
        </div>
    </body>
    </html>';

    $admin_email = get_option("admin_email");
	$subject = 'Anniversaire ! '. get_the_title();
    wp_mail($admin_email,$subject,$message, $headers);
    echo "Email Sent Successfully";
    
    endwhile;
    endif;
	wp_die(); // this is required to terminate immediately and return a proper response
}