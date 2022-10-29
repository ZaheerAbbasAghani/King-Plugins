<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action('wp_ajax_nopriv_anam_edit_dokument','anam_edit_dokument' );
add_action( 'wp_ajax_anam_edit_dokument','anam_edit_dokument' );
function anam_edit_dokument() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_dokument_info';
$id = $_POST['id'];
$query = "SELECT * FROM $table_name WHERE id='".$id."' ";
$results = $wpdb->get_results($query);

?>

<div class="mainClassDokument">

	<form method="post" action="" id="dokument_update_form" enctype="multipart/form-data" name="dokument_form">


		<label> Behandlung
			<div class="frmSearch">
				<input type="text" name="search_treatments" id="search_treatments" placeholder="Search for Treatments" value="<?php echo $results[0]->treatment_name;?>" required/>
				<div class="autocomplete-box"></div>
			</div>
		</label>

		<label> Preis
			<input type="text" name="tprice" id="tprice" value="<?php echo $results[0]->price;?>" readonly/>
		</label>

		<label> Zusätzliche Informationen
			<textarea name="addition_information" cols="5" rows="5"><?php echo $results[0]->addition_information;?></textarea>
		</label>

		<label> Zahlungsmittel

		<?php 
			$table_name5 = $wpdb->base_prefix.'anam_payment_methods';
			$query = "SELECT * FROM $table_name5";
			$fetch = $wpdb->get_results($query);
		?>

		<select name="payment_methods">
			<?php foreach ($fetch as $value) { ?>
				<option value="<?php echo $value->payment_method_name; ?>" <?php if($value->payment_method_name == $results[0]->payment_methods){ echo "selected"; } ?>> <?php echo $value->payment_method_name; ?>  </option>
			<?php } ?>
		</select>
		</label>

		<label> E-Mail
			<input type="text" name="email_pdf" value="<?php echo $results[0]->email_pdf;?>"/>
		</label>

		<label> Bezahlstatus
			<select name="payment_status" required>
				<option value=""> Select Payment Method</option>
				<option value="paid" <?php if($results[0]->status == 'paid'){ echo 'selected'; } ?>> Paid</option>
				<option value="pending" <?php if($results[0]->status == 'pending'){ echo 'selected'; } ?>> Pending</option>
			</select>
		</label>
		<input type="hidden" name="doc_id" value="<?php echo $id;?>"/>
		<input type="hidden" name="user_id" value="<?php echo $results[0]->customer_id;?>"/>
		<input type="hidden" name="doctor_id" value="<?php echo $results[0]->doctor_id;?>"/>
		<input type="submit" class="button" value="Senden" />

	</form>

</div>

<?php 

	wp_die();
}