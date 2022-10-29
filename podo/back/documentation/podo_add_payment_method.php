<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action( 'wp_ajax_nopriv_podo_add_payment', 'podo_add_payment' );
add_action( 'wp_ajax_podo_add_payment', 'podo_add_payment' );
function podo_add_payment() {

$info = "";

$info .= '
<div class="pform">
	<form method="post" action id="payment_information">
		<div class="columnn">
			
			<div style="margin-left:10px;display:none;" class="upBox">
			<label>Bild des Zahlungsmittels</label>
				<a href="#" class="payment_method_image button button-primary" id="payment_method_image">Hochladen</a>
				 <img src="#" id="payImageDisplay"/>
				 <input type="hidden" name="QRImage" id="QRImage">
			</div>
			<label>Name des Zahlungsmittels
				<input type="text" name="payment_method_name" id="payment_method_name" placeholder="Payment Method Name" required>
			</label>
			<label>Zahlungsmittel Beschreibung
				<input type="text" name="payment_method_description" id="payment_method_description" placeholder="Payment Method Description"  required>
			</label>
			
		</div>
		<div class="columnn">
			<label>Hat das Zahlungsmittel einen QR Code?
				  <input type="checkbox" id="enableQR" name="enableQR" style="">
			</label>
		</div>
		
		<input type="submit" class="button button-primary" value="Senden">
	</form>
</div>';

echo $info;

	wp_die();
}