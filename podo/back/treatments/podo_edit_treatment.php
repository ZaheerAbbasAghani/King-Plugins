<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action( 'wp_ajax_nopriv_podo_edit_treatment', 'podo_edit_treatment' );
add_action( 'wp_ajax_podo_edit_treatment', 'podo_edit_treatment' );
function podo_edit_treatment() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_treatments_list';
$id = $_POST['id'];
$query = "SELECT * FROM $table_name WHERE id='".$id."' ";
$results = $wpdb->get_results($query);


//print_r($results);

$info .= ' <h1> Behandlung bearbeiten </h1><hr>
<form method="post" action="" id="podo_field_update"  name="podo_field_update" enctype="multipart/form-data" class="podo_treatments">

<label>Name:
	<input type="text" name="treatment_name" id="treatment_name" value="'.$results[0]->name.'" required/>
</label>

<label>Preis:
	<input type="number" name="treatment_price" id="treatment_price" value="'.$results[0]->price.'" required/>
</label>

<label>Dauer:
	<input type="text" name="treatment_duration" id="treatment_duration" value="'.$results[0]->duration.'" required/>
</label>

<label>Beschreibung:
	<textarea name="treatment_description" id="treatment_description" rows="5" style="width:100%;">'.$results[0]->description.'</textarea>
</label>


<input type="hidden" value="'.$id.'" id="field_id"/>
<input type="submit" class="button button-primary" value="Aktuallisieren" id="update_all_treatments"/>
<a href="#" data-id="'.$id.'" class="delete_treatment" style="color: #fff;text-decoration: none;background: red;padding: 10px 22px;border-radius: 4px;display: block;width: 13%;float: right;margin-right:-17px;position:relative;text-align:center;font-size:16px;">Löschen</a>

</form>



';

echo $info;


wp_die();

}