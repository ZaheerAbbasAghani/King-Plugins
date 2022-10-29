<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action( 'wp_ajax_nopriv_anam_edit_customer', 'anam_edit_customer' );
add_action( 'wp_ajax_anam_edit_customer', 'anam_edit_customer' );
function anam_edit_customer() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_customer_info';
$id = $_POST['id'];
$query = "SELECT * FROM $table_name WHERE id='".$id."' ";
$results = $wpdb->get_results($query);

$info .= '<span class="section_title">Customer Information</span>
<div class="cform">
	<form method="post" action id="update_new_customer">
		<div class="column">
			<label>First Name:
				<input name="first_name" id="first_name" placeholder="Enter your first name" value="'.$results[0]->first_name.'" required>
			</label>
			<label>Last Name:
				<input name="last_name" id="last_name" placeholder="Enter your last name" value="'.$results[0]->last_name.'" required>
			</label>
			<label>Email:
				<input type="email" name="email_address" id="email_address" placeholder="Enter your email address" value="'.$results[0]->email_address.'" required>
			</label>
		</div>
		<div class="column">
			<label>Gender:
				<select id="gender" name="gender" required>
				';
if($results[0]->gender == "Male") {
	$info .='<option value="Male" selected>Male</option>';
	$info .='<option value="Female">Female</option>';
}
if($results[0]->gender == "Female") {
	$info .='<option value="Male">Male</option>';
	$info .='<option value="Female" selected>Female</option>';
}

$info .='</select>
			</label>
			<label>Birthday:
				<input type="date" name="birth_date" id="birth_date" placeholder="Birth Date" value="'.$results[0]->birth_date.'" required>
			</label>
			<label>Mobile Phone:
				<input type="tel" name="mobile_no" id="mobile_no" placeholder="Enter your mobile no" value="'.$results[0]->mobile_no.'" required>
			</label>
		</div>
		<div class="column">
			<label>Job:
				<input name="job" id="job" placeholder="Enter your job"  value="'.$results[0]->job.'" required>
			</label>
			<label>City:
				<input name="city" id="city" placeholder="Enter your city name" value="'.$results[0]->city.'" required>
			</label>
			<label>Zip Code:
				<input name="zipcode" id="zipcode" placeholder="Enter your zip code" value="'.$results[0]->zipcode.'" required>
			</label>
		</div>
		<div class="column_full">
			<label>First Appointment Date:
				<input type="date" name="fad" id="fad" placeholder="Enter your first appointment date" value="'.$results[0]->fad.'" required>
			</label>
			<label>Address:
				<textarea placeholder="Enter your address" name="address" id="address" row="5" cols="5">'.$results[0]->address.'</textarea>
			</label>
		</div><span class="section_title2">Medical Information</span>
		<div class="column">
			<label>Doctor: <input type="text" name="doctor_name" id="doctor_name" value="'.$results[0]->doctor_name.'"required></label>
			<label>Diagnosis:
				<input name="diagnosis" id="diagnosis" placeholder="Enter your diagnosis details"  value="'.$results[0]->diagnosis.'" required>
			</label>
			<label>Phone of Doctor :
				<input type="tel" name="phone_of_doctor" id="phone_of_doctor" placeholder="Enter doctor phone number" value="'.$results[0]->phone_of_doctor.'"  required>
			</label>
		</div>
		<div class="column">
			<label>Drugs:
				<input name="drugs" id="drugs" placeholder="Enter drugs details" value="'.$results[0]->drugs.'" required>
			</label>
			<label>Insurance Company:
				<input name="insurance_company" id="insurance_company" placeholder="Enter your insurance company details" value="'.$results[0]->insurance_company.'"  required>
			</label>
		</div>
		<div class="column_full">
			<label>Important Notes:
				<textarea placeholder="Enter important notes" name="important_notes" id="important_notes" row="5" cols="5">'.$results[0]->important_notes.'</textarea>
			</label>
		</div>
		<input type="hidden" value="'.$results[0]->id.'" name="cid" id="cid">
		<input type="submit" class="button" value="Update">
	</form>
</div>';

echo $info;

	wp_die();
}