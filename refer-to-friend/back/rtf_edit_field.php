<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action( 'wp_ajax_nopriv_rtf_edit_field', 'rtf_edit_field' );
add_action( 'wp_ajax_rtf_edit_field', 'rtf_edit_field' );
function rtf_edit_field() {

global $wpdb;
$table_name = $wpdb->base_prefix.'rtf_fields_maker';
$id = $_POST['id'];
$query = "SELECT * FROM $table_name WHERE id='".$id."' ";
$results = $wpdb->get_results($query);


$info = "";

$info .= '<div class="wrap_field_form">

<form method="post" action="" id="podo_field_update"  name="podo_field_update" enctype="multipart/form-data">

<label> Name des Eintrags:
	<input type="text" name="field_label" id="field_label" value="'.$results[0]->label.'" required/>
</label>

<label> Art des Eintrags:
	<select id="field_type">
		<option value="" selected disabled>Select a field </option>';
	
		$info .= '<option value="text"'.($results[0]->fieldtype == "text" ? "selected" : "").'>Text</option>';
		$info .= '<option value="text"'.($results[0]->fieldtype == "email" ? "selected" : "").'>Email</option>';
		$info .= '<option value="textarea"'.($results[0]->fieldtype == "textarea" ? "selected" : "").'>Textarea</option>';
		$info .= '<option value="date"'.($results[0]->fieldtype == "date" ? "selected" : "").'>Date</option>';

		$info .= '<option value="checkbox"'.($results[0]->fieldtype == "checkbox" ? "selected" : "").'>Checkbox</option>';
$info .= '</select>
</label>
<input type="hidden" value="'.$id.'" id="field_id"/>
<input type="submit" class="button" value="Update" id="update_all_fields"/>

</form>

</div>';

echo $info;


wp_die();

}