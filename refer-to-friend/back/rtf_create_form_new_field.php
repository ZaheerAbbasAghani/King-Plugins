<?php 

add_action( 'wp_ajax_rtf_create_form_new_field', 'rtf_create_form_new_field' );
add_action( 'wp_ajax_nopriv_rtf_create_form_new_field', 'rtf_create_form_new_field' );

function rtf_create_form_new_field() {

echo '<div class="wrap_field_form">

	<form method="post" action="" id="podo_field_maker"  name="podo_field_maker" enctype="multipart/form-data">

		<label> Field Name:
			<input type="text" name="field_label" id="field_label" required/>
		</label>

		<label> Field Type:
			<select id="field_type">
				<option value="" selected disabled>Select a field </option>
				<option value="text">Text</option>
				<option value="email">Email</option>
				<option value="textarea">Text Area</option>
				<option value="date">Date</option>
				<option value="checkbox">Checkbox</option>
				<option value="select">Dropdown</option>
			</select>
		</label>


		<label> Placeholder Text:
			<input type="text" name="field_placeholder" id="field_placeholder" required/>
		</label>


		<input type="submit" class="button button-primary" value="Submit" id="create_all_fields"/>

	</form>	
</div>';


wp_die();


}