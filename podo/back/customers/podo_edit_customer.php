<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action( 'wp_ajax_nopriv_podo_edit_customer_dashboard', 'podo_edit_customer_dashboard' );
add_action( 'wp_ajax_podo_edit_customer_dashboard', 'podo_edit_customer_dashboard' );
function podo_edit_customer_dashboard() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_customer_info';
$id = $_POST['id'];
$query = "SELECT * FROM $table_name WHERE id='".$id."' ";
$results = $wpdb->get_results($query);
$info = "";
$info .= '<span class="section_title">Patienten Informationen</span>
<div class="cform">
	<form method="post" action id="update_new_customer_dashboard">
		<div class="column">
			<label>Vorname:
				<input name="first_name" id="first_name" placeholder="Geben Sie Ihren Vornamen ein" value="'.$results[0]->first_name.'" required>
			</label>
			<label>Nachname:
				<input name="last_name" id="last_name" placeholder="Geben Sie Ihren Nachnamen ein" value="'.$results[0]->last_name.'" required>
			</label>
			<label>E-Mail:
				<input type="email" name="email_address" id="email_address" placeholder="Geben Sie Ihre E-Mail-Adresse ein" value="'.$results[0]->email_address.'">
			</label>
		</div>
		<div class="column">
			<label>Geschlecht:
				<select id="gender" name="gender" required>
				';
if($results[0]->gender == "Herr") {
	$info .='<option value="Herr" selected>Herr</option>';
	$info .='<option value="Frau">Frau</option>';
}
if($results[0]->gender == "Frau") {
	$info .='<option value="Herr">Herr</option>';
	$info .='<option value="Frau" selected>Frau</option>';
}

$info .='</select>
			</label>
			<label>Geburtstag:
				<input type="date" name="birth_date" id="birth_date" placeholder="Birth Date" value="'.$results[0]->birth_date.'" required>
			</label>
			<label>Handynummer:
				<input type="tel" name="mobile_no" id="mobile_no" placeholder="Geben Sie Ihre Handynummer ein" value="'.$results[0]->mobile_no.'" required>
			</label>
		</div>
		<div class="column">
			<label>Beruf:
				<input name="job" id="job" placeholder="Geben Sie Ihren Beruf ein"  value="'.$results[0]->job.'">
			</label>
			<label>Wohnort:
				<input name="city" id="city" placeholder="Geben Sie Ihren Wohnort ein" value="'.$results[0]->city.'" required>
			</label>
			<label>Postleitzahl:
				<input name="zipcode" id="zipcode" placeholder="Geben Sie Ihre Postleitzahl ein" value="'.$results[0]->zipcode.'" required>
			</label>
		</div>
		<div class="column_full">
			<label>Adresse:
				<textarea placeholder="Geben Sie Ihre Adresse ein" name="address" id="address" rows="5" cols="5">'.$results[0]->address.'</textarea>
			</label>

			<label>Datum der ersten Behandlung:
				<input type="date" name="fad" id="fad" placeholder="Enter your first appointment date" value="'.$results[0]->fad.'" rows="5" cols="5">
			</label>
			
		</div><span class="section_title2">Medizinische Informationen</span>
		<div class="column">
			<label>Arzt: <input type="text" name="doctor_name" id="doctor_name" value="'.$results[0]->doctor_name.'" placeholder="Geben Sie den Namen Ihres Arztes ein"></label>
			<label>Diagnose:
				<input name="diagnosis" id="diagnosis" placeholder="Geben Sie Ihre Diagnose ein"  value="'.$results[0]->diagnosis.'">
			</label>
			<label>Geben Sie die Nummer Ihres Arztes ein:
				<input type="tel" name="phone_of_doctor" id="phone_of_doctor" placeholder="Geben Sie die Telefonnummer des Arztes ein" value="'.$results[0]->phone_of_doctor.'" >
			</label>
		</div>
		<div class="column">
			<label>Medikamente
				<input name="drugs" id="drugs" placeholder="Medikamente" value="'.$results[0]->drugs.'">
			</label>
			<label>Versicherung:
				<input type="text" name="insurance_company" id="insurance_company" placeholder="Bei welcher Versicherung sind Sie?" value="'.$results[0]->insurance_company.'">
			</label>

			<label>Vorerkrankungen:
				<input type="text" name="vorerkrankungen" id="vorerkrankungen" placeholder="Vorerkrankungen" value="'.$results[0]->vorerkrankungen.'">
			</label>

		</div>
		<div class="column_full">
			<label>Wichtige Informationen:
				<textarea placeholder="Enter important notes" name="important_notes" id="important_notes" rows="5" cols="5" placeholder="Müssen wir etwas Wichtiges wissen?">'.$results[0]->important_notes.'</textarea>
			</label>
		</div>
		<input type="hidden" value="'.$results[0]->id.'" name="cid" id="cid">
		<input type="submit" class="button button-primary" value="Senden">
	</form>
</div>';

echo $info;

	wp_die();
}