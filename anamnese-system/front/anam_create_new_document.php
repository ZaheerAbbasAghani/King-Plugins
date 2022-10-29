<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_anam_create_new_document', 'anam_create_new_document' );
add_action( 'wp_ajax_anam_create_new_document', 'anam_create_new_document' );
function anam_create_new_document() {
	
global $wpdb;
$table_name = $wpdb->base_prefix.'anam_customer_info';
$c_id = $_POST['id'];
$d_id = get_current_user_id();

echo '<div class="mainClass">

<form method="post" action="" id="docform" enctype="multipart/form-data" name="docform">

<div class="foot_deformation">
<h4> Foot Deformation </h4>

<label> Senkfuss
	<select name="senkfuss">
		<option>None</option>
		<option>Left</option>
		<option>Right</option>
	</select>
</label>


<label> Spreizfuss
	<select name="spreizfuss">
		<option>None</option>
		<option>Left</option>
		<option>Right</option>
	</select>
</label>


<label> Knickfuss nach innen
	<select name="knickfuss_nach_innen">
		<option>None</option>
		<option>Left</option>
		<option>Right</option>
	</select>
</label>

<label> Knickfuss nach aussen
	<select name="knickfuss_nach_aussen">
		<option>None</option>
		<option>Left</option>
		<option>Right</option>
	</select>
</label>

<label> Hohlfuss
	<select name="hohlfuss">
		<option>None</option>
		<option>Left</option>
		<option>Right</option>
	</select>
</label>

<label> Plattfuss
	<select name="plattfuss">
		<option>None</option>
		<option>Left</option>
		<option>Right</option>
	</select>
</label>

<label> Fusschwellung
	<select name="fusschwellung">
		<option>None</option>
		<option>Left</option>
		<option>Right</option>
	</select>
</label>

<label> Other foot deformation
	<input type="text" name="other_foot_deformation">
</label>
</div><!-- deformation -->

<div class="foot_deformation">
<h4> Krampfadern </h4>

<label> Oberschenkel
	<select name="oberschenkel">
		<option value="None">None</option>
		<option>Link</option>
		<option>Retchs</option>
	</select>
</label>


<label> Unterschenkel
	<select name="unterschenkel">
		<option value="None">None</option>
		<option>Link</option>
		<option>Retchs</option>
	</select>
</label>

<h4> Einlagen </h4>

<label> Konfektion
	<input type="text" name="konfektion">
</label>

<label> Nach Mass
	<input type="text" name="nach_mass">
</label>

<h4> Risks </h4>
<div style="margin-bottom: 47px;">
<label> Diabetis
	<input type="checkbox" value="Diabetis" name="risks[]">
</label>

<label> Allergien
	<input type="checkbox" value="Allergien" name="risks[]">
</label>

<label> Gerinnungshemmer
	<input type="checkbox" value="Gerinnungshemmer" name="risks[]">
</label>
</div>
<label> Infektionskrankheiten
	<input type="text" name="infektionskrankheiten">
</label>

</div><!-- Krampfadern -->


<div class="foot_deformation">
<h4> Findings </h4>

<label style="float:left;margin-right:8px;"> Hornhaut
	<input type="checkbox" value="Hornhaut" name="findings[]">
</label>

<label style="float:left;margin-right:8px;"> Hallux Valgus
	<input type="checkbox" value="Hallux Valgus" name="findings[]">
</label>

<label style="float:left;margin-right:8px;"> Warzen
	<input type="checkbox" value="Warzen" name="findings[]">
</label>

<label style="float:left;margin-right:8px;"> Hautpilz
	<input type="checkbox" value="Hautpilz" name="findings[]">
</label> 

<label style="clear:both;"> Wunden
	<input type="text"  name="wunden">
</label>

<label> Huhneraugen auf Zehen
	<select name="huhneraugen_auf_zehen">
		<option value="None">None</option>
		<option value="links">Links</option>
		<option value="rechts">Rechts</option>
		<option value="beide">Beide</option>
	</select>
</label>


<label> Hammerzehen
	<select name="hammerzehen">
		<option value="None">None</option>
		<option value="links">Links</option>
		<option value="rechts">Rechts</option>
		<option value="beide">Beide</option>
	</select>
</label>

<label> Nagelpilz
	<select name="nagelpilz">
		<option value="None">None</option>
		<option value="links">Links</option>
		<option value="rechts">Rechts</option>
		<option value="beide">Beide</option>
	</select>
</label>

<label> Eingewachsene Nagel
	<select name="eingewachsene_nagel">
		<option value="None">None</option>
		<option value="links">Links</option>
		<option value="rechts">Rechts</option>
		<option value="beide">Beide</option>
	</select>
</label>


<label> Zustand de Nagel
	<input type="text" name="zustand_de_nagel">
	<input type="hidden" name="user_id" value="'.$c_id.'"/>
	<input type="hidden" name="doctor_id" value="'.$d_id.'"/>
</label>

<label> Bilder hochladen 
	 <input type="file" name="upimg[]" accept="image/*" class="files-data form-control" multiple />
</label>


<input type="submit" value="Submit" class="button">
</div><!-- deformation -->

</form>


</div><!--mainClass-->';
	wp_die();
}