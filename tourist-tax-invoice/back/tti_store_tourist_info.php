<?php 

add_action( 'wp_ajax_tti_store_tourist_info', 'tti_store_tourist_info' );
add_action( 'wp_ajax_nopriv_tti_store_tourist_info', 'tti_store_tourist_info' );

function tti_store_tourist_info() {
	global $wpdb; // this is how you get access to the database

    $table_name = $wpdb->base_prefix.'tti_tourist_bookings';

    $fullname = $_POST['fullname'];
    $surname = $_POST['surname'];
    $address = $_POST['address'];
    $zipcode = $_POST['zipcode'];
    $city = $_POST['city'];
    $number_of_person = $_POST['number_of_person'];
    $collection = serialize(json_decode(stripcslashes($_POST['collection'])));
    $time = current_time( 'mysql' );

    $query = "SELECT invoice_id FROM $table_name ORDER BY id DESC LIMIT 1";
	$r = $wpdb->get_results($query);

	$invoice = $r[0]->invoice_id + 1;


	$query_results=$wpdb->insert($table_name, array("fullname" => $fullname, 'surname' => $surname, 'address' => $address, 'zipcode' => $zipcode, 'city' => $city, 'number_of_person' => $number_of_person, 'invoice_id' => $invoice, 'collection' => $collection, 'created_date' => $time),array("%s","%s","%s","%s","%s","%s","%s","%s"));


	if($query_results == 1){
		//echo "Record Inserted Successfully";



$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Zaheer Abbas');
$pdf->SetTitle('Invoice');
$pdf->SetSubject('Invoice');

//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
//$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
///$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
//$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

	$lastid = $wpdb->insert_id;
	
	$query = "SELECT * FROM $table_name WHERE id='$lastid'";
    $results = $wpdb->get_results($query);
    $logo = get_option('uploaded_logo');
   /* $query = "SELECT * FROM $table_name ORDER BY id DESC";
    $results = $wpdb->get_results($query);*/

$html = "";

$html .= '

<table>
<tr style="vertical-align:top;">
	<td style="width:200px"><img src="'.$logo.'" style="width:200px;"></td>
	<td style="width:440px; text-align:right;"> 
		<span style="font-size:20px;">Rechnung Kurtaxe</span><br>
		<span>Rechnungsnummer:</span>'.$results[0]->invoice_id.'<br>
		<span>Rechnungsdatum:</span>'.date("d.m.Y",strtotime($results[0]->created_date)).'
	</td>
</tr>
</table>

<br/>
<br/>
<br/>
<h3>Kunde:</h3>
<p>
'.$results[0]->surname.' '.$results[0]->fullname.'<br>
'.$results[0]->address.'<br>
'.$results[0]->zipcode.' '.$results[0]->city.'
</p>

<style type="text/css">.invoice-table { border-collapse: collapse; font-size: 14px; line-height: 26px; margin-top: 31px; width: 100%; }.invoice-table th { background: #ddd; font-size: 24px; font-weight: bold; height: 66px; padding: 12px 16px; text-align: center; }.invoice-table th, .invoice-table td { border: 1px solid #ddd; }.invoice-table td { height: 52px; padding: 12px 16px; }.invoice-table td.empty { border: none; width: 50%; }.invoice-table td.multi-accom-empty { border: none; width: 48%; }.invoice-table td.multi-accom-indent { background: #eee; border-bottom: none; border-top: none; padding: 0; width: 2%; }.invoice-table .multi-accom-separator td { background: #ddd; height: 2px; padding: 0; }.invoice-table td.subpart-title { background: #eee; font-size: 18px; font-weight: bold; height: 66px; text-align: center; }.invoice-table td.subsubpart-title { background: #eee; font-weight: bold; }.total td { font-size: 18px; height: 66px; }.subtotal .amount, .total .amount { position: relative; }.subtotal .amount:before, .total .amount:before { background: #ddd; content: ""; display: block; left: 0; position: absolute; right: 0; top: 0; }.subtotal .amount:before { height: 3px; }.total .amount:before { height: 7px; }.total td { font-size: 16px; text-transform: uppercase; }.total td.multi-accom-total { font-size: 14px; }.desc-head { width: 75%; }.amount-head { width: 25%; }.amount, .fee-final, .fee-included, .subtotal-text, .coupon-text, .discount-text, .total-text { text-align: right; }.subtotal-text, .total-text, .subtotal .amount, .total .amount { font-weight: bold }</style><table class="invoice-table"><tr><th colspan="3" class="desc-head">Beschreibung</th><th class="amount-head">Betrag</th></tr>

        ';
            
$prices = array();
	
	$html .= '<table class="invoice-table" style="background:#ddd;">';
		foreach (unserialize($results[0]->collection) as  $collection) {
			array_push($prices, $collection->price * $collection->nights);

    		$html .= '<tr><td colspan="3" style="padding:10px;">';
   		 	$html .= '1 Person x '.$collection->price.' Euro x '.$collection->nights.' N&auml;chte';
    		$html .= '</td> <td class="amount" style="padding:10px;">'.number_format($collection->price * $collection->nights, 2).' &euro;</td>
        	</tr>';
        }
        
    	$html .= '<tr class="total" style="padding:10px;"><td colspan="2" class="empty" style="padding:10px;"></td>
        <td class="total-text" style="padding:10px;">Gesamtsumme</td><td class="amount">'.number_format(array_sum($prices), 2).' &euro;</td></tr>

    </table>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<p style="color:#666;">
Wir bitten um eine Begleichtung der Rechnung innerhalb von 14 Tagen nach Erhalt. Überweisen Sie den vollständigen Betrag auf folgendes Konto:<br/>
<b>Empfänger:</b> Klaus-Dieter Schulz<br/>
<b>Bank:</b> Postbank Stuttgart<br/>
<b>IBAN:</b> DE 9460 0100 7003 1366 8704<br/>
<b>BIC/SWIFT:</b> PBNKDEFFXXX<br/>
<b>Verwendungszweck:</b> Rechnung Schloonsee <b>Nr. '.$results[0]->invoice_id.'</b>
</p>

<footer><p  style="color:#666;"> Familie Schulz - Badstraße 3+5 - 17429 Bansin - info@schloonsee.de<br/>Steuernummer: XXXXXXXX beim Finanzamt XXXXXX // USt.-ID: XXXX </p></footer>';


// Set some content to print
$html = <<<EOD
$html
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

$wp_upload_dir =  wp_upload_dir();
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$d = strtotime(date("Y-m-d g:h:i"));
$r = $pdf->Output(WP_CONTENT_DIR . '/uploads/TTIPDF/'.$lastid.'.pdf', 'F');




    $logo = get_option('uploaded_logo');


    echo '<div id="invoice-meta">
<h2>Rechnung Kurtaxe</h2>
<span>Rechnungsnummer:</span>'.$results[0]->invoice_id.'<br>
<span>Rechnungsdatum:</span>'.date("d.m.Y",strtotime($results[0]->created_date)).'
</div>

<h2><img src="'.$logo.'" width="300"></h2>
<br/>
<br/>
<br/>
<h3>Kunde:</h3>
<p>
'.$results[0]->surname.' '.$results[0]->fullname.'<br>
'.$results[0]->address.'<br>
'.$results[0]->zipcode.' '.$results[0]->city.'
</p>

<style type="text/css">.invoice-table { border-collapse: collapse; font-size: 14px; line-height: 26px; margin-top: 31px; width: 100%; }.invoice-table th { background: #ddd; font-size: 24px; font-weight: bold; height: 66px; padding: 12px 16px; text-align: center; }.invoice-table th, .invoice-table td { border: 1px solid #ddd; }.invoice-table td { height: 52px; padding: 12px 16px; }.invoice-table td.empty { border: none; width: 50%; }.invoice-table td.multi-accom-empty { border: none; width: 48%; }.invoice-table td.multi-accom-indent { background: #eee; border-bottom: none; border-top: none; padding: 0; width: 2%; }.invoice-table .multi-accom-separator td { background: #ddd; height: 2px; padding: 0; }.invoice-table td.subpart-title { background: #eee; font-size: 18px; font-weight: bold; height: 66px; text-align: center; }.invoice-table td.subsubpart-title { background: #eee; font-weight: bold; }.total td { font-size: 18px; height: 66px; }.subtotal .amount, .total .amount { position: relative; }.subtotal .amount:before, .total .amount:before { background: #ddd; content: ""; display: block; left: 0; position: absolute; right: 0; top: 0; }.subtotal .amount:before { height: 3px; }.total .amount:before { height: 7px; }.total td { font-size: 16px; text-transform: uppercase; }.total td.multi-accom-total { font-size: 14px; }.desc-head { width: 75%; }.amount-head { width: 25%; }.amount, .fee-final, .fee-included, .subtotal-text, .coupon-text, .discount-text, .total-text { text-align: right; }.subtotal-text, .total-text, .subtotal .amount, .total .amount { font-weight: bold }</style><table class="invoice-table"><tr><th colspan="3" class="desc-head">Beschreibung</th><th class="amount-head">Betrag</th></tr>

        ';
            
$prices = array();
echo '<table class="invoice-table">';
$wp_upload_dir =  wp_upload_dir();
$custom_upload_folder = $wp_upload_dir['baseurl'].'/'."TTIPDF";

		foreach (unserialize($results[0]->collection) as  $collection) {
		array_push($prices, $collection->price * $collection->nights);

		echo '<tr><td colspan="3">';
    		echo '1 Person x '.$collection->price.' Euro x '.$collection->nights.' N&auml;chte';
    	echo '</td> <td class="amount">'.number_format($collection->price * $collection->nights, 2).' &euro;</td>
        </tr>';
        }
        
        echo '<tr class="total"><td colspan="2" class="empty"></td>
        <td class="total-text">Gesamtsumme</td><td class="amount">'.number_format(array_sum($prices), 2).' &euro;</td></tr>

    </table>
<br/>
<p>
Wir bitten um eine Begleichtung der Rechnung innerhalb von 14 Tagen nach Erhalt. Überweisen Sie den vollständigen Betrag auf folgendes Konto:<br/>
<b>Empfänger:</b> Klaus-Dieter Schulz<br/>
<b>Bank:</b> Postbank Stuttgart<br/>
<b>IBAN:</b> DE 9460 0100 7003 1366 8704<br/>
<b>BIC/SWIFT:</b> PBNKDEFFXXX<br/>
<b>Verwendungszweck:</b> Rechnung Schloonsee <b>Nr. '.$results[0]->invoice_id.'</b>
</p>

<footer>Familie Schulz - Badstraße 3+5 - 17429 Bansin - info@schloonsee.de<br/>Steuernummer: XXXXXXXX beim Finanzamt XXXXXX // USt.-ID: XXXX</footer>

<a href="'.$custom_upload_folder.'/'.$lastid.'.pdf" class=" button button-default" download> Download PDF </a>';




//$attachments = array( WP_CONTENT_DIR . '/uploads/'.'dokument_'.$d.'.pdf' );


	}else{
		echo "Something went wrong!";
	}
	
	

	wp_die(); // this is required to terminate immediately and return a proper response
}