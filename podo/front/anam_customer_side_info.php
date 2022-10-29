<?php 
function anam_customer_side_info($info){

$info .= '
<span class="section_title">Patienten Informationen</span>
<div class="cform">
	<form method="post" action id="create_new_customer2">
		<div class="column">
			<label>Vorname:
				<input name="first_name" id="first_name" placeholder="Geben Sie Ihren Vornamen ein" required>
			</label>
			<label>Nachname:
				<input name="last_name" id="last_name" placeholder="Geben Sie Ihren Nachnamen ein" required>
			</label>
			<label>E-Mail:
				<input type="email" name="email_address" id="email_address" placeholder="Geben Sie Ihre E-Mail-Adresse ein">
			</label>
		</div>
		<div class="column">
			<label>Geschlecht:
				<select id="gender" name="gender">
					<option value disabled selected>Geschlecht</option>
					<option value="Mann">Mann</option>
					<option value="Frau">Frau</option>
				</select>
			</label>
			<label>Geburtstag:
				<input type="date" name="birth_date" id="birth_date" placeholder="Geburtstag" required>
			</label>
			<label>Handynummer:
				<input type="tel" name="mobile_no" id="mobile_no" placeholder="Handynummer" required>
			</label>
		</div>
		<div class="column">
			<label>Beruf:
				<input name="job" id="job" placeholder="Geben Sie Ihren Beruf ein">
			</label>
			<label>Wohnort:
				<input name="city" id="city" placeholder="Geben Sie Ihren Wohnort ein" required>
			</label>
			<label>Postleitzahl:
				<input name="zipcode" id="zipcode" placeholder="Geben Sie Ihre Postleitzahl ein" required>
			</label>
		</div>
		<div class="column_full">
			<label>Adresse:
				<textarea placeholder="Geben Sie Ihre Adresse ein" name="address" id="address" row="5" cols="5"></textarea>
			</label>
			
			<label>Datum der ersten Behandlung:
				<input type="date" name="fad" id="fad" placeholder="Datum der ersten Behandlung">
			</label>
			
		</div><span class="section_title2">Medizinische Informationen</span>
		<div class="column">
			<label>Arzt:
				<input type="text" name="doctor_name" id="doctor_name" placeholder="Geben Sie den Namen Ihres Arztes ein">
			</label>
			<label>Diagnose:
				<input name="diagnosis" id="diagnosis" placeholder="Geben Sie Ihre Diagnose ein">
			</label>
			<label>Telefonnummer des Arztes:
				<input type="tel" name="phone_of_doctor" id="phone_of_doctor" placeholder="Geben Sie die Telefonnummer des Arztes ein">
			</label>
		</div>
		<div class="column">
			<label>Medikamenta:
				<input name="drugs" id="drugs" placeholder="Nehmen Sie Medikamente?">
			</label>
			<label>Versicherung:
				<input name="insurance_company" id="insurance_company" placeholder="Bei welcher Versicherung sind Sie?">
			</label>

			<label>Vorerkrankungen: 
				<input name="vorerkrankungen" id="vorerkrankungen" placeholder="Vorerkrankungen">
			</label>

		</div>
		<div class="column_full">
			<label>Wichtige Informationen:
				<textarea placeholder="Müssen wir etwas Wichtiges wissen?" name="important_notes" id="important_notes" row="5" cols="5"></textarea>
			</label>
			<label class="terms"><input type="checkbox" name="agree_on_terms" required="">Ich bin einverstanden, dass meine persönlichen Daten für Behandlungszwecke gespeichert werden </label>
		</div>
		<input type="submit" class="button" value="Senden">
	</form>
</div>';
return $info;
}

add_shortcode('customer_info', 'anam_customer_side_info');