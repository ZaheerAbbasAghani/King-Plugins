<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_podo_create_anamnese_new_field', 'podo_create_anamnese_new_field' );
add_action( 'wp_ajax_podo_create_anamnese_new_field', 'podo_create_anamnese_new_field' );
function podo_create_anamnese_new_field() {

//	$id = $_POST['id'];

echo '<div class="mainClassDokument">

	<form method="post" action="" id="podo_field_maker"  name="podo_field_maker" enctype="multipart/form-data">

		<label> Name des Eintrags:
			<input type="text" name="field_label" id="field_label" required/>
		</label>

		<label> Art des Eintrags:
			<select id="field_type">
				<option value="" selected disabled>Select a field </option>
				<option value="text">Text</option>
				<option value="textarea">Text Bereich</option>
				<option value="date">Datum</option>
				<option value="checkbox">Checkbox</option>
			</select>
		</label>

		<input type="submit" class="button" value="Erstellen" id="create_all_fields"/>

	</form>


	

</div>';


//<div id="podo_field_preview"><b>Field Preview</b><hr></div>


	wp_die();
}
