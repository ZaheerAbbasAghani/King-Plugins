<?php 

if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
require_once(plugin_dir_path(__FILE__) . 'tcpdf_min/tcpdf.php');

add_action( 'wp_ajax_tti_download_invoice', 'tti_download_invoice' );
add_action( 'wp_ajax_nopriv_tti_download_invoice', 'tti_download_invoice' );

function tti_download_invoice() {
	global $wpdb; // this is how you get access to the database

    $table_name = $wpdb->base_prefix.'tti_tourist_bookings';

    $id = $_POST['id'];

    $query = "SELECT * FROM $table_name WHERE id='$id'";
    $results = $wpdb->get_results($query);

    $logo = get_option('uploaded_logo');

   // print_r($results);


/*$data = '<div id="invoice-meta">
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

foreach (unserialize($results[0]->collection) as  $collection) {

array_push($prices, $collection->price * $collection->nights);

$data = '<tr><td colspan="3">';
   $data = '1 Person x '.$collection->price.' Euro x '.$collection->nights.' N&auml;chte';
    $data = '</td> <td class="amount">'.number_format($collection->price * $collection->nights, 2).' &euro;</td>
        </tr>';
        }
        
        $data = '<tr class="total"><td colspan="2" class="empty"></td>
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

<a href="#" class="downloadPdf button button-default" data-id="'.$id.'"> Download PDF </a>';*/



/*$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 001');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
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
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
$html = <<<EOD
<h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
<i>This is the first example of TCPDF library.</i>
<p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
<p>Please check the source code documentation and other examples for further information.</p>
<p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

$wp_upload_dir =  wp_upload_dir();
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$d = strtotime(date("Y-m-d g:h:i"));
$r = $pdf->Output('HELLO.pdf', 'I');

//$attachments = array( WP_CONTENT_DIR . '/uploads/'.'dokument_'.$d.'.pdf' );

/*
$to = "aghanizaheer@gmail.com";
$subject = "A Test";
$message = "Message";
$headers = array('Content-Type: text/html; charset=UTF-8');
wp_mail( $to, $subject, $message, $headers, $attachments);
*/

    
	wp_die(); // this is required to terminate immediately and return a proper response
}