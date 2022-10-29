<?php 

if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
require_once(plugin_dir_path(__FILE__) . 'tcpdf_min/tcpdf.php');

add_action( 'wp_ajax_tti_generate_invoice', 'tti_generate_invoice' );
add_action( 'wp_ajax_nopriv_tti_generate_invoice', 'tti_generate_invoice' );

function tti_generate_invoice() {
	global $wpdb; // this is how you get access to the database

    $table_name = $wpdb->base_prefix.'tti_tourist_bookings';

    $id = $_POST['id'];

    $query = "SELECT * FROM $table_name WHERE id='$id'";
    $results = $wpdb->get_results($query);

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
<a href="'.$custom_upload_folder.'/'.$id.'.pdf" class=" button button-default" download> Download PDF </a>';

    
	wp_die(); // this is required to terminate immediately and return a proper response
}