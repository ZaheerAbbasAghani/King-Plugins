<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_anam_create_new_dokument', 'anam_create_new_dokument' );
add_action( 'wp_ajax_anam_create_new_dokument', 'anam_create_new_dokument' );
function anam_create_new_dokument() {

	$id = $_POST['id'];

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_payment_methods';


echo '<div class="mainClassDokument">

	<form method="post" action="" id="dokument_form"  name="dokument_form" enctype="multipart/form-data">

		<label> Behandlung:
			<div class="frmSearch">
				<input type="text" name="search_treatments" id="search_treatments" placeholder="Nach Behandlungen suchen" required/>
				<div class="autocomplete-box"></div>
			</div>
		</label>

		<label> Preis
			<input type="text" name="tprice" id="tprice" readonly disabled/>
		</label>

		<label> Zusätzliche Informationen
			<textarea name="addition_information" id="addition_information" rows="10" cols="10"></textarea>
		</label>

		<label> <span style="margin-top: 12px;display: block;"> Bilder hochladen </span>
			<input type="file" name="upimg" style="padding:14px;" class="files-data" multiple/>
		</label>

		<hr>


		<h4 style="text-align:left;"> Zahlung </h4>';


		echo '<label> <span style="margin-top: 12px;display: block;"> Zahlungsmittel </span><select id="payment_methods" required><option value="" disabled selected>Zahlungsmittel wählen</option>';
	
	$query ="SELECT * FROM $table_name";
	$results = $wpdb->get_results($query);

	foreach ($results as $result) {
		echo "<option value='".$result->payment_method_name."'>".$result->payment_method_name."</option>";
	}
	echo '</select></label>';

	echo "<div class='QRcode'></div>";


	global $wpdb;
    $table_name2 = $wpdb->base_prefix.'anam_customer_info';
    $query2 ="SELECT * FROM $table_name2 WHERE id='$id'";
	$results2 = $wpdb->get_results($query2);

	echo '<label> <span style="margin-top: 12px;display: block;"> Rechnung per E-Mail senden an: </span>
			<input type="text" name="email_pdf" class="email_pdf" value="'.$results2[0]->email_address.'"/>
		</label>';


	echo '<label> <span style="margin-top: 12px;display: block;"> Bezahlstatus </span>
			<select id="payment_status" required>
				<option value="" disabled selected>Zahlungsstatus wählen</option>
				<option value="paid">Bezahlt</option>
				<option value="pending">Ausstehend</option>
			</select>

		</label>';



echo	'<input type="hidden" id="user_id" value="'.$id.'"/>
		<input type="hidden" id="duration" />
		<input type="hidden" id="doctor_id" value="'.get_current_user_id().'"/>
		<input type="submit" class="button"/>

	</form>

</div>';





	wp_die();
}
