<?php 

require_once __DIR__ . '/vendor/autoload.php';

add_action( 'wp_ajax_vm_invoice_generator', 'vm_invoice_generator' );
add_action( 'wp_ajax_nopriv_vm_invoice_generator', 'vm_invoice_generator' );

function vm_invoice_generator() {
	global $wpdb; // this is how you get access to the database


	$id = $_POST['post_id'];

	$license_plate = get_post_meta( $id, '_vw_license_plate', true);
	$year = get_post_meta( $id, '_vw_year', true);
	$make = get_post_meta( $id, '_vw_make', true);
	$model = get_post_meta( $id, '_vw_model', true);
	$price = get_post_meta( $id, '_vw_price', true);


    $payment = get_post_meta( $id, '_payment_date_time', true );
    $parked  = get_post_meta( $id, '_parked_date_time', true );
    $washed  = get_post_meta( $id, '_washed_date_time', true );
    $email 	= get_post_meta( $id, '_vm_email_address', true );

	$config = array(
		'format' => 'A4',
		'mode' => 'utf-8',
		'orientation' => 'P',
		'margin_top' => 0,
		'margin_left' => 0,
		'margin_right' => 0,
		'margin_header' => 0,
		'setAutoTopMargin' => 'pad',
	);
	
	$mpdf = new \Mpdf\Mpdf($config);
	$html = "";

	$stylesheet=file_get_contents('css/print.css',\Mpdf\HTMLParserMode::HEADER_CSS);
	$mpdf->WriteHTML($stylesheet,1);

	$html .= "<div class='invoice_wrapper'> 

		<div class='invoice_header_left'>
			<img src='https://multi-tidy.com/wp-content/uploads/2022/05/Logo.jpeg' class='logo'>
		</div>
		<div class='invoice_header_right'>

			<h1> INVOICE </h1>
			<p> Invoice #: ".$id." </p>
			<p> Invoice Date: ".date('d/m/Y')." </p>

		</div>
	</div>

	<div class='invoice_body'>

		<table border='1'> 
			<tr> 
				<th> Vin Number (Last 4) </th>
				<th> License Plate </th>
				<th> Year </th>
				<th> Make </th>
				<th> Model </th>
				<th> Price </th>
			</tr>
			<tr>
				<td> ".get_the_title( $id )." </td>
				<td> ".$license_plate." </td>
				<td> ".$year." </td>
				<td> ".$make." </td>
				<td> ".$model." </td>
				<td> ".$price." </td>
			</tr>
		</table>

	</div>

	<p class='bottomDetails'> Payment Date Time: <b> $payment </b> </p>
	<p class='bottomDetails'> Parked Date Time: <b> $parked </b> </p>
	<p class='bottomDetails'> Washed Date Time: <b> $washed </b> </p>


	<h1 class='beforeAfter'> Before & After Pictures <hr></h1>


	<p class='bottomDetails'> <a href='".get_the_permalink($id)."' style='color:#000; font-size:18px;'> Click here </a></p>";
	/*$upload_dir = wp_upload_dir();
	$directory = $upload_dir['basedir'].'/vehicle-before-after/'.$id.'/before';
	$images = glob($directory . "/*.{jpg,png,jpeg,gif,PNG}",GLOB_BRACE);
	$fileList = [];

	$html .= "<table class='listofImages'><tr>";
	foreach($images as $image)
	{
		$imgName = basename($image);
		$img = get_site_url().'/wp-content/uploads/vehicle-before-after/'.$id.'/before/'.$imgName;
		$html .= "<td style='width:250px;'><img src='".$img."' style='width:100%;height:100%;'> </td>";
	}

	$html .= "</tr></table><h1> After <hr></h1>";

	$upload_dir = wp_upload_dir();
	$directory = $upload_dir['basedir'].'/vehicle-before-after/'.$id.'/after';
	$images = glob($directory . "/*.{jpg,png,jpeg,gif,PNG}",GLOB_BRACE);
	$fileList = [];

	$html .= "<table class='listofImages'><tr>";
	foreach($images as $image)
	{
		$imgName = basename($image);
		$img = get_site_url().'/wp-content/uploads/vehicle-before-after/'.$id.'/after/'.$imgName;
		$html .= "<td style='width:250px;'><img src='".$img."' style='width:100%;height:100%;'> </td>";
	}

	$html .= "</tr></table>";*/


	$mpdf->WriteHTML($html);

	$upload_dir = wp_upload_dir();
	$directory=$upload_dir['basedir'].'/vehicle-before-after/'.date("Ymdghi").".pdf";

	$mpdf->Output($directory,'F');

	$subject = 'A receipt received from website '. get_bloginfo('name');
	$message = 'A receipt with all details is in attachment.';
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$attachments = array(WP_CONTENT_DIR . '/uploads/vehicle-before-after/'.date("Ymdghi").".pdf");

	wp_mail($email, $subject, $message, $headers,$attachments);

	echo "Sent";

	exit;


	wp_die(); // this is required to terminate immediately and return a proper response
}