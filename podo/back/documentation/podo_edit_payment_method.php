<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action( 'wp_ajax_nopriv_podo_edit_payment_method', 'podo_edit_payment_method' );
add_action( 'wp_ajax_podo_edit_payment_method', 'podo_edit_payment_method' );
function podo_edit_payment_method() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_payment_methods';
$id = $_POST['id'];
$query = "SELECT * FROM $table_name WHERE id='".$id."' ";
$results = $wpdb->get_results($query);

$info .= '
<div class="pform">
	<form method="post" action id="update_payment_information">
		<div class="columnn">
			<label>Payment Method Image </label>
			<div style="margin-left:10px;">
				<a href="#" class="payment_method_image button button-primary" id="payment_method_image">Upload</a>
				 <img src="'.$results[0]->payImage.'" id="payImageDisplay"/>
				 <input type="hidden" name="payImage" id="payImage" value="'.$results[0]->payImage.'">
			</div>
			<label>Payment Method Name
				<input type="text" name="payment_method_name" id="payment_method_name" value="'.$results[0]->payment_method_name.'" required>
			</label>
			<label>Payment Method Description
				<input type="text" name="payment_method_description" id="payment_method_description" value="'.$results[0]->payment_method_description.'"  required>
			</label>
			
		</div>
		<div class="columnn">
			<label>Do you need QR code uploader? (Optional)';

			if($results[0]->enableQR == 1){
				$info .= '<input type="checkbox" id="enableQR" name="enableQR" checked>';
			}else{
				$info .= '<input type="checkbox" id="enableQR" name="enableQR">';
			}
			$info .= '</label>
		</div>
		
		<input type="submit" class="button button-primary" value="Senden">
	</form>
</div>';

echo $info;

	wp_die();
}