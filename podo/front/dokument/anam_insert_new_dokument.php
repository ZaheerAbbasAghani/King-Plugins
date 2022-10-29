<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
require_once(plugin_dir_path(__FILE__) . 'vendor/autoload.php');
add_action( 'wp_ajax_nopriv_anam_insert_new_dokument', 'anam_insert_new_dokument' );
add_action( 'wp_ajax_anam_insert_new_dokument', 'anam_insert_new_dokument' );
function anam_insert_new_dokument() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_dokument_info';

$treatment_name = $_POST['search_treatments'];
$currency = get_option('podo_currency');
$tprice = str_replace($currency, ' ', $_POST['tprice']);
$addition_information = $_POST['addition_information'];
$email_pdf = $_POST['email_pdf'];
$payment_methods = $_POST['payment_methods'];
$payment_status = $_POST['payment_status'];
$duration = $_POST['duration'];
$user_id = $_POST['user_id'];
$doctor_id = $_POST['doctor_id'];
$now = current_time('mysql');


$insert = $wpdb->query("INSERT INTO $table_name (treatment_name,price, addition_information,payment_methods,email_pdf,status,customer_id,doctor_id,created_at) VALUES('$treatment_name','$tprice','$addition_information','$payment_methods', '$email_pdf','$payment_status','$user_id','$doctor_id','$now')");

if($insert == 1){
	
    if(!empty($_FILES["upimg"]["tmp_name"])){
        $error=array();
        $extension=array("jpeg","jpg","png","gif");
        $wp_upload_dir =  wp_upload_dir();
        $lastid = $wpdb->insert_id;
        $custom_upload_folder= $wp_upload_dir['basedir'].'/anam_users/'.$lastid;

        if(!is_dir($custom_upload_folder)){
        	mkdir($custom_upload_folder);
        }

        foreach($_FILES["upimg"]["tmp_name"] as $key=>$tmp_name) {
            $file_name=$_FILES["upimg"]["name"][$key];
            $file_tmp=$_FILES["upimg"]["tmp_name"][$key];
            $ext=pathinfo($file_name,PATHINFO_EXTENSION);

            if(in_array($ext,$extension)) {
                if(!file_exists($custom_upload_folder."/".$file_name)) {
                    move_uploaded_file($file_tmp=$_FILES["upimg"]["tmp_name"][$key],$custom_upload_folder."/".$file_name);
                 //   echo "SUCCESS";
                }
                else {
                    $filename=basename($file_name,$ext);
                    $newFileName=$filename.time().".".$ext;
                     move_uploaded_file($file_tmp=$_FILES["upimg"]["tmp_name"][$key],$custom_upload_folder."/".$newFileName);
                }
            }
            else {
                array_push($error,"$file_name, ");
            }
        }

    }

	$mpdf = new \Mpdf\Mpdf();

	$company_name = get_option('podo_company_name');
	$company_logo = get_option('podo_company_logo');

	$html = "";


	//$html .= "<div class='wrapper'> </div>";

	//GET USERNAME BY ID

	$table_name2 = $wpdb->base_prefix.'anam_customer_info';

	$query2 = "SELECT * FROM $table_name2 WHERE id='".$user_id."' ORDER BY created_at ASC";
	$customer = $wpdb->get_results($query2);
	$username = $customer[0]->first_name.' '.$customer[0]->last_name;
	$address = $customer[0]->address;
	$czipcode = $customer[0]->zipcode;
	$ccity = $customer[0]->city;
	$today = date("d.m.Y");
	$tday = date("Y-m").'-'.rand(10,1000);
	$due_date = date("d.m.Y", strtotime("14 day", strtotime($today)));

	$html = "";
	$footer = "";

	$html .= '<div class="wrapper">
	    <div style="text-align:left;line-height:60px;float:left;width:70%;">
	        <h1> '.$company_name.' </h1>
	    </div>

	    <div style="text-align:right;float:right;width:30%;"> 
	        <img src="'.$company_logo.'" style="width:120px;height:100px"/>
	      
	    </div>

	    <div style="line-height:20px;clear:both; margin-top:40px;"> 
	        <div style="text-align:left;float:left;width:70%;"> '.$username.' <br> '.$address.' <br> '.$czipcode.' - '.$ccity.' </div> 

	        <div style="text-align:right;float:left;width:30%;"><b> Invoice #'.$tday.' </b> <hr> <br> Invoice Date: <span style="text-align:right;"> '.$today.' </span><br> Due Date: <span style="text-align:right;">'.$due_date.' </span></div>
	    </div>

	    <ul style="clear:both;width:100%;margin-top:50px; list-style:none;margin-left:0px;padding-left:0px;">

	            <li style="font-weight:bold;">Service Description</li>
	            <li style="font-weight:bold;">Duration</li>
	            <li style="font-weight:bold;">Price</li>

	            <li style="border-top:1px solid #444;vertical-align:middle;">'.$treatment_name.'</li>
	            <li style="border-top:1px solid #444;vertical-align:middle;">'.$duration.'</li>
	            <li style="border-top:1px solid #444;vertical-align:middle;">'.$tprice.' '.$currency.'</li>

	            <li style="border-top:1px solid #444;"></li>
	            <li style="border-top:1px solid #444;"><b> Total </b> </li>
	            <li style="border-top:1px solid #444;"><b>'.$tprice.' '.$currency.'</b></li>
	    </ul>';


	$table_name3 = $wpdb->base_prefix.'anam_payment_methods';
	$query3 = "SELECT * FROM $table_name3 ORDER BY created_at ASC";
	$payments = $wpdb->get_results($query3);

	foreach ($payments as $payment) {
	    
	    if($payment->payment_method_name == $payment_methods){
	        $html .= "<div style='margin-top:50px;'> <p>Payment Method: </p><b> ".$payment->payment_method_name."</b></div>";
	        if($payment->enableQR == 1 && $payment_status=="pending"){
	            $html .= "<img src='".$payment->QRImage."' style='width: 130px;height: 125px;margin-top:20px;'>";
	        }
	    }


	}

	$address = get_option('podo_address_number');
	$phone = get_option('podo_phone');
	$website = get_option('podo_website');
	$podo_bank_name = get_option('podo_bank_name');
	$podo_name_of_account = get_option('podo_name_of_account');
	$podo_iban = get_option('podo_iban');
	$podo_bic = get_option('podo_bic');
	$podo_zipcode = get_option('podo_zipcode');
	$podo_city = get_option('podo_city');
	
	$footer .= "<div class='dk_footer'> <div style='float:left;width:300px;float:left'>

	 	<p>".$address."<br>".$podo_zipcode." - ".$podo_city." <br> <br> Bank: ".$podo_bank_name." </br> 
	 	<br> Name of Account: ".$podo_name_of_account." </br>
		<br>IBAN: ".$podo_iban." </br>
		<br>BIC: ".$podo_bic." </br>

		<p style='margin-top:20px;'> Page 1 of 1 from Invoice #".$tday." </p>
	 </div>

	 <div style='float:right;width:300px;text-align:right;'> 
	 	<p>Phone:".$phone." <br> Website: ".$website." </p>

	

	 </div></div>";

	$html .= '<style>
	ul li{
		width:27.3%;
		float:left;
		text-align:center;
		padding:20px;

	}
	.dk_footer p{
		margin-bottom:0px !important;
	}
	.dk_footer{
		text-align:left;
		color:#777;
		font-size:12px;
		font-style:normal;
	}
	</style>';

	$mpdf->WriteHTML($html);
	$mpdf->SetFooter($footer);
	$lastid = $wpdb->insert_id;

	$fetch = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$lastid' ORDER BY ID DESC LIMIT 1");
	$d = date("d.m.Y",strtotime($fetch[0]->created_at));

	$wp_upload_dir =  wp_upload_dir();
	$filename = $d.'-'.$customer[0]->last_name;
	$mpdf->Output($wp_upload_dir['basedir'] . '/Invoice-'.$filename.'.pdf', 'F');

	$attachments = array(WP_CONTENT_DIR.'/uploads/'.'Invoice#'.$filename.'.pdf');

	/* 
		Email Setup 
	*/ 

	$gender = $customer[0]->gender;
	$last_name = $customer[0]->last_name;

	$to = $email_pdf;

	if(get_option('podo_currency') == "CHF"){
	
		$subject = get_option('podo_company_name').' Rechnung';
		$message = "<div style='font-size:16px;'> Sehr geehrte(r) $gender $last_name <br><br> 
		<p> Danke, dass Sie sich für unsere Praxis entschieden haben. <br> Unten finden Sie alle Details zu Ihrer Behandlung bei uns. Im Anhang befindet sich ausserdem eine PDF-Kopie der Rechnung. </p> <hr>

		<ul style='padding:0px;list-style:none;'>
			<li style='width:32%; display:inline-block;margin:0px;'>$treatment_name</li>
			<li style='width:32%; display:inline-block;margin:0px;'>$duration</li>
			<li style='width:32%; display:inline-block;margin:0px;'>".$_POST['tprice']."</li>
		</ul>

		<p> Wir danken Ihnen für Ihr Vertrauen und hoffen Sie bald wieder empfangen zu dürfen. <br> 
			Viele Grüsse
	 	</p></div>

	 	".get_option('podo_company_name')." ";


		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail( $to, $subject, $message, $headers, $attachments);
	}
	if(get_option('podo_currency') == "€"){

		$subject = get_option('podo_company_name').' Rechnung';
		$message = "<div style='font-size:16px;'><br> Sehr geehrte(r) $gender $last_name <br> 
		<p> Danke, dass Sie sich für unsere Praxis entschieden haben. <br> Unten finden Sie alle Details zu Ihrer Behandlung bei uns. Im Anhang befindet sich ausserdem eine PDF-Kopie der Rechnung. </p> <hr>

		<ul style='padding:0px;list-style:none;'>
			<li style='width:31%; display:inline-block;margin:0px;'>$treatment_name</li>
			<li style='width:31%; display:inline-block;margin:0px;'>$duration</li>
			<li style='width:31%; display:inline-block;margin:0px;'>".$_POST['tprice']." </li>
		</ul>

		<p> Wir danken Ihnen für Ihr Vertrauen und hoffen Sie bald wieder empfangen zu dürfen. <br> 
			Viele Grüsse
	 	</p></div>

	 	".get_option('podo_company_name')." ";


		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail( $to, $subject, $message, $headers, $attachments);
	}


    $response = array("message" => "Neue Patienten Dokumentation wurde erfolgreich erstellt.", "status" => 1);

	echo wp_send_json($response);

}else{
	$response = array("message" => "Etwas ist schief gelaufen!", "status" => 0);
	echo wp_send_json($response);
}
  

	wp_die();
}