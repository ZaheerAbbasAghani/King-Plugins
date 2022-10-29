<?php 

require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;


if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action('wp_ajax_gift_registry_receiver_records','gift_registry_receiver_records' );
add_action('wp_ajax_nopriv_gift_registry_receiver_records','gift_registry_receiver_records');
function gift_registry_receiver_records() {
	global $wpdb;

	global $wp;
	$current_url = home_url( add_query_arg( array(), $wp->request ) );
	

	$params = array();
	parse_str($_POST['formData'], $params);

	//print_r($params);

	$user_email 	= $params['user_email'];
	$user_phone 	= $params['user_phone'];
	$your_message 	= $params['your_message'];
	$user_id = get_current_user_id();

	$user = get_user_by('id', $user_id);

	$products = $_POST['products'];

	$table_name = $wpdb->base_prefix.'gft_gift_registry_users';
	$query = "SELECT * FROM $table_name WHERE user_id='$user_id' AND user_email='$user_email' AND user_phone='$user_phone'";
	$query_results = $wpdb->get_results($query);

	$message = "";

	if(count($query_results) == 0) {

		foreach ($products as $product) {
			
			$rowResult = $wpdb->insert($table_name, 
				array(	"product_id" => $product, 
						"user_id" => get_current_user_id(), 
						"user_email" => $user_email, 
						"user_phone" => $user_phone, 
						"status" => 0, 
						"created_at" => gmdate('Y-m-d H:i:s')), 
				array("%s","%d","%s","%s","%d","%s"));

		}

		$to = $user_email;


		$first_name = $user->first_name == "" ? "" : $user->first_name;
		$last_name = $user->last_name == "" ? "" : $user->last_name;

		$fullname = $first_name.' '.$last_name == "" ? $user->display_name : $first_name.' '.$last_name;


		$subject = $fullname ." is inviting you to view their gift registry!";
	

		// Email Template

		$message .= '<!DOCTYPE html>
		<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
		<head>
		    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
		    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn\'t be necessary -->
		    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
		    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
		    <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->


		    <!-- CSS Reset : BEGIN -->
		    <style>

			body,html{margin:0 auto!important;padding:0!important;height:100%!important;width:100%!important;background:#f1f1f1;}*{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}div[style*="margin: 16px 0"]{margin:0!important}table,td{mso-table-lspace:0pt!important;mso-table-rspace:0pt!important}table{border-spacing:0!important;border-collapse:collapse!important;table-layout:fixed!important;margin:0 auto!important}img{-ms-interpolation-mode:bicubic}a{text-decoration:none}.aBn,.unstyle-auto-detected-links *,[x-apple-data-detectors]{border-bottom:0!important;cursor:default!important;color:inherit!important;text-decoration:none!important;font-size:inherit!important;font-family:inherit!important;font-weight:inherit!important;line-height:inherit!important}.a6S{display:none!important;opacity:.01!important}.im{color:inherit!important}img.g-img+div{display:none!important}@media only screen and (min-device-width:320px) and (max-device-width:374px){u~div .email-container{min-width:320px!important}}@media only screen and (min-device-width:375px) and (max-device-width:413px){u~div .email-container{min-width:375px!important}}@media only screen and (min-device-width:414px){u~div .email-container{min-width:414px!important}}.primary{background:#17bebb}.bg_white{background:#fff}.bg_light{background:#f7fafa}.bg_black{background:#000}.bg_dark{background:rgba(0,0,0,.8)}.email-section{padding:2.5em}.btn{padding:10px 15px;display:inline-block}.btn.btn-primary{border-radius:5px;background:#17bebb;color:#fff}.btn.btn-white{border-radius:5px;background:#fff;color:#000}.btn.btn-white-outline{border-radius:5px;background:0 0;border:1px solid #fff;color:#fff}.btn.btn-black-outline{border-radius:0;background:0 0;border:2px solid #000;color:#000;font-weight:700}.btn-custom{color:rgba(0,0,0,.3);text-decoration:underline}h1,h2,h3,h4,h5,h6{color:#000;margin-top:0;font-weight:400}body{font-weight:400;font-size:15px;line-height:1.8;color:rgba(0,0,0,.4)}a{color:#17bebb}.logo h1{margin:0}.logo h1 a{color:#17bebb;font-weight:700;font-family:"Work Sans,sans-serif"}.hero{position:relative;z-index:0}.hero .text{color:rgba(0,0,0,.3)}.hero .text h2{color:#000;font-size:34px;margin-bottom:15px;font-weight:300;line-height:1.2}.hero .text h3{font-weight:200}.hero .text h2 span{font-weight:600;color:#000}.product-entry{display:block;position:relative;float:left;padding-top:20px}.product-entry .text{width:calc(100% - 125px);padding-left:20px}.product-entry .text h3{margin-bottom:0;padding-bottom:0}.product-entry .text p{margin-top:0}.product-entry .text,.product-entry img{float:left}ul.social{padding:0}ul.social li{display:inline-block;margin-right:10px}.footer{border-top:1px solid rgba(0,0,0,.05);color:rgba(0,0,0,.5)}.footer .heading{color:#000;font-size:20px}.footer ul{margin:0;padding:0}.footer ul li{list-style:none;margin-bottom:10px}.footer ul li a{color:#000}

		    </style>


		</head>

		<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f1f1f1;">
			<center style="width: 100%; background-color: #f1f1f1;">
		    <div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
		      &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
		    </div>
		    <div style="max-width: 600px; margin: 0 auto;" class="email-container">
		    	<!-- BEGIN BODY -->
		      <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
		      	<tr>
		          <td valign="top" class="bg_white" style="padding: 1em 2.5em 0 2.5em;">
		          	<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
		          		<tr>
		          			<td class="logo" style="text-align: left;">
					            <h1><a href="'.get_the_permalink().'"><img src="https://www.geekpassionsgifts.com/wp-content/uploads/2022/04/B31AB250-EA58-4F76-A4D4-80BB3FEF27A3.jpeg" style="width: 150px;display: block;margin: auto;"></a></h1>
					          </td>
		          		</tr>
		          	</table>
		          </td>
			      </tr><!-- end tr -->
						<tr>
		          <td valign="middle" class="hero bg_white" style="padding: 2em 0 2em 0;">
		            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
		            	<tr>
		            		<td style="padding: 0 2.5em; text-align: left;">
		            			<div class="text">
		            				<h1>'.$subject.'</h1>
		            				<h3>'.$your_message.'</h3>

		            				<h3 style="color:red;"> Below are some items they have selected for your viewing. </h3>

		            			</div>
		            		</td>
		            	</tr>
		            </table>
		          </td>
			      </tr><!-- end tr -->
			      <tr>
			      	<table class="bg_white" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
			      		<tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
							    <th width="80%" style="text-align:left; padding: 0 2.5em; color: #000; padding-bottom: 20px">Item</th>
							    <th width="20%" style="text-align:right; padding: 0 2.5em; color: #000; padding-bottom: 20px">Price</th>
							  </tr>';
					

					foreach ($products as $product) {		

					 $pro = wc_get_product( $product );
					 $description = wp_trim_words($pro->get_description(),30,'...');

					  $thumb = get_the_post_thumbnail_url($product, 'full' );
					  $message .= '<tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
					  	<td valign="middle" width="80%" style="text-align:left; padding: 0 2.5em;"><a href="'.$current_url.'/gift-registry'.'/?email='.$user_email.'&phone='.$user_phone.' "> 
					  		<div class="product-entry">';
					  
					 if ($pro->get_type() == "simple") {
					 
					 	$message .= '<img src="'.$thumb.'" alt="'.get_the_title( $product ).'" style="width: 100px; max-width: 600px; height: auto; margin-bottom: 20px; display: block;">';
					 }else{


					 	$variation=new WC_Product_Variation( $product );
						$image_id = $variation->get_image_id();

						$image_array = wp_get_attachment_image_src($image_id, 'thumbnail');
							
						$thumb = $image_array[0];

						$message .= '<img src="'.$thumb.'" alt="'.get_the_title( $product ).'" style="width: 100px; max-width: 600px; height: auto; margin-bottom: 20px; display: block;">';


					 }


					  $message .= '<div class="text">
					  				<h3>'.get_the_title( $product ).'</h3>
					  				
					  				<p style="color:#000;">'.$description.'</p>
					  			</div>
					  		</div></a>
					  	</td>
					  	<td valign="middle" width="20%" style="text-align:left; padding: 0 2.5em;"><a href="'.$current_url.'/gift-registry'.'/?email='.$user_email.'&phone='.$user_phone.' ">';
			
						if ($pro->get_type() == "simple") {
				        
				        	$sale_price     =  $pro->get_sale_price();
				        	$regular_price  =  $pro->get_regular_price();

				        	$message .= '<span class="price" style="color:#000;font-size:20px;display: block;text-align: right;"> '.get_woocommerce_currency_symbol().''.$regular_price.'</span>';
					    }
					    elseif($pro->get_type() == "variation"){
					        $sale_price     =  $pro->get_sale_price();
				        	$regular_price  =  $pro->get_regular_price();

					         $message .= '<span class="price" style="color:#000;font-size:20px;display: block;text-align: right;"> '.get_woocommerce_currency_symbol().''.$regular_price.'</span>';
					    }


					  	
					  	$message .= '</a></td>
					  </tr>';
					}
							  
			    $message .= '</table>
			      </tr><!-- end tr -->
		      <!-- 1 Column Text + Button : END -->
		      </table>
		  
		    </div>
		  </center>
		</body>
		</html>';

		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail( $to, $subject, $message, $headers);


		// Message Template
		$sid 			= 	get_option('gft_account_sid');
		$token 		=  	get_option('gft_auth_token');
		$sender 	=  	get_option('gft_twilio_phone_number');

		$phone_message = "Hi, $fullname is inviting you to view their Geek Passions Gifts Registry! Please follow their personal link below to view their selected items. 
			\nHappy Geeking!
			\n$current_url/gift-registry
			\nGeek Passions Gifts\nwww.geekpassionsgifts.com";
			
		$client = new Client($sid, $token);
		$client->messages->create(
		    // Where to send a text message (your cell phone?)
		    $user_phone,
		    array(
		        'from' => $sender,
		        'body' => strip_tags($phone_message)
		    )
		);

		
		$response=array("status"=>1,"message"=>"Gift Registry Link Sent.");
		wp_send_json( $response );

	}else{
		$response=array("status"=>2,"message"=>"Gift Registry Link Already Sent.");
		wp_send_json( $response );			
	}



	wp_die();
}