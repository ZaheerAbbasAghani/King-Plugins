<?php 
function anam_customer_side_info($info){

$info .= '
<span class="section_title">Customer Information</span>
<div class="cform">
	<form method="post" action id="create_new_customer">
		<div class="column">
			<label>First Name:
				<input name="first_name" id="first_name" placeholder="Enter your first name" required>
			</label>
			<label>Last Name:
				<input name="last_name" id="last_name" placeholder="Enter your last name" required>
			</label>
			<label>Email:
				<input type="email" name="email_address" id="email_address" placeholder="Enter your email address" required>
			</label>
		</div>
		<div class="column">
			<label>Gender:
				<select id="gender" name="gender" required>
					<option value disabled selected>Gender</option>
					<option value="Male">Male</option>
					<option value="Female">Female</option>
				</select>
			</label>
			<label>Birthday:
				<input type="date" name="birth_date" id="birth_date" placeholder="Birth Date" required>
			</label>
			<label>Mobile Phone:
				<input type="tel" name="mobile_no" id="mobile_no" placeholder="Enter your mobile no" required>
			</label>
		</div>
		<div class="column">
			<label>Job:
				<input name="job" id="job" placeholder="Enter your job" required>
			</label>
			<label>City:
				<input name="city" id="city" placeholder="Enter your city name" required>
			</label>
			<label>Zip Code:
				<input name="zipcode" id="zipcode" placeholder="Enter your zip code" required>
			</label>
		</div>
		<div class="column_full">
			<label>First Appointment Date:
				<input type="date" name="fad" id="fad" placeholder="Enter your first appointment date" required>
			</label>
			<label>Address:
				<textarea placeholder="Enter your address" name="address" id="address" row="5" cols="5"></textarea>
			</label>
		</div><span class="section_title2">Medical Information</span>
		<div class="column">
			<label>Doctor:
				<input type="text" name="doctor_name" id="doctor_name" required>
			</label>
			<label>Diagnosis:
				<input name="diagnosis" id="diagnosis" placeholder="Enter your diagnosis details" required>
			</label>
			<label>Phone of Doctor :
				<input type="tel" name="phone_of_doctor" id="phone_of_doctor" placeholder="Enter doctor phone number" required>
			</label>
		</div>
		<div class="column">
			<label>Drugs:
				<input name="drugs" id="drugs" placeholder="Enter drugs details" required>
			</label>
			<label>Insurance Company:
				<input name="insurance_company" id="insurance_company" placeholder="Enter your insurance company details" required>
			</label>
		</div>
		<div class="column_full">
			<label>Important Notes:
				<textarea placeholder="Enter important notes" name="important_notes" id="important_notes" row="5" cols="5"></textarea>
			</label>
		</div>
		<input type="submit" class="button" value="Submit">
	</form>
</div>';
return $info;
}

add_shortcode('customer_info', 'anam_customer_side_info');