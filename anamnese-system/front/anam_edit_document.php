<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}
add_action('wp_ajax_nopriv_anam_edit_document','anam_edit_document' );
add_action( 'wp_ajax_anam_edit_document','anam_edit_document' );
function anam_edit_document() {

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_document_info';
$id = $_POST['id'];
$query = "SELECT * FROM $table_name WHERE id='".$id."' ";
$results = $wpdb->get_results($query);

//print_r($results);

?>
<div class="mainClass">

<form method="post" action="" id="docUpdateform">

<div class="foot_deformation">
<h4> Foot Deformation </h4>

<label> Senkfuss
	<select name="senkfuss">
		<option <?php if($results[0]->senkfuss == "None"){
			echo "selected";} ?>>None</option>
		<option <?php if($results[0]->senkfuss == "Left"){
			echo "selected"; } ?>>Left</option>
		<option <?php if($results[0]->senkfuss == "Right"){
			echo "selected"; } ?>>Right</option>
	</select>
</label>


<label> Spreizfuss
	<select name="spreizfuss">
		<option <?php if($results[0]->spreizfuss == "None"){
			echo "selected";} ?>>None</option>
		<option <?php if($results[0]->spreizfuss == "Left"){
			echo "selected"; } ?>>Left</option>
		<option <?php if($results[0]->spreizfuss == "Right"){
			echo "selected"; } ?>>Right</option>
	</select>
</label>


<label> Knickfuss nach innen
	<select name="knickfuss_nach_innen">
		<option <?php if($results[0]->knickfuss_nach_innen == "None"){
			echo "selected";} ?>>None</option>
		<option <?php if($results[0]->knickfuss_nach_innen == "Left"){
			echo "selected"; } ?>>Left</option>
		<option <?php if($results[0]->knickfuss_nach_innen == "Right"){
			echo "selected"; } ?>>Right</option>
	</select>
</label>

<label> Knickfuss nach aussen
	<select name="knickfuss_nach_aussen">
		<option <?php if($results[0]->knickfuss_nach_aussen == "None"){
			echo "selected";} ?>>None</option>
		<option <?php if($results[0]->knickfuss_nach_aussen == "Left"){
			echo "selected"; } ?>>Left</option>
		<option <?php if($results[0]->knickfuss_nach_aussen == "Right"){
			echo "selected"; } ?>>Right</option>
	</select>
</label>

<label> Hohlfuss
	<select name="hohlfuss">
		<option <?php if($results[0]->hohlfuss == "None"){
			echo "selected";} ?>>None</option>
		<option <?php if($results[0]->hohlfuss == "Left"){
			echo "selected"; } ?>>Left</option>
		<option <?php if($results[0]->hohlfuss == "Right"){
			echo "selected"; } ?>>Right</option>
	</select>
</label>

<label> Plattfuss
	<select name="plattfuss">
		<option <?php if($results[0]->plattfuss == "None"){
			echo "selected";} ?>>None</option>
		<option <?php if($results[0]->plattfuss == "Left"){
			echo "selected"; } ?>>Left</option>
		<option <?php if($results[0]->plattfuss == "Right"){
			echo "selected"; } ?>>Right</option>
	</select>
</label>

<label> Fusschwellung
	<select name="fusschwellung">
		<option <?php if($results[0]->fusschwellung == "None"){
			echo "selected";} ?>>None</option>
		<option <?php if($results[0]->fusschwellung == "Left"){
			echo "selected"; } ?>>Left</option>
		<option <?php if($results[0]->fusschwellung == "Right"){
			echo "selected"; } ?>>Right</option>
	</select>
</label>

<label> Other foot deformation
	<input type="text" name="other_foot_deformation" value="<?php echo $results[0]->other_foot_deformation; ?>">
</label>
</div><!-- deformation -->

<div class="foot_deformation">
<h4> Krampfadern </h4>

<label> Oberschenkel
	<select name="oberschenkel">
		<option <?php if($results[0]->oberschenkel == "None"){
			echo "selected";} ?>>None</option>
		<option <?php if($results[0]->oberschenkel == "Link"){
			echo "selected";} ?>>Link</option>
		<option <?php if($results[0]->oberschenkel == "Retchs"){
			echo "selected"; } ?>>Retchs</option>
	</select>
</label>


<label> Unterschenkel
	<select name="unterschenkel">
		<option <?php if($results[0]->unterschenkel == "None"){
			echo "selected";} ?>>None</option>
		<option <?php if($results[0]->unterschenkel == "Link"){
			echo "selected";} ?>>Link</option>
		<option <?php if($results[0]->unterschenkel == "Retchs"){
			echo "selected"; } ?>>Retchs</option>
	</select>
</label>

<h4> Einlagen </h4>

<label> Konfektion
	<input type="text" name="konfektion" value="<?php echo $results[0]->konfektion; ?>">
</label>

<label> Nach Mass
	<input type="text" name="nach_mass" value="<?php echo $results[0]->nach_mass; ?>">
</label>

<h4> Risks </h4>
<div style="margin-bottom: 47px;">
<label> Diabetis
	<?php $risks = unserialize($results[0]->risks); ?>
	<?php if(in_array('Diabetis', $risks)){ ?>
		<input type="checkbox" value="Diabetis" name="risks[]" checked="checked">
	<?php } else{ ?>
		<input type="checkbox" value="Diabetis" name="risks[]">
	<?php } ?>
</label>

<label> Allergien
	<?php $risks = unserialize($results[0]->risks); ?>
	<?php if(in_array('Allergien', $risks)){ ?>
		<input type="checkbox" value="Allergien" name="risks[]" checked="checked">
	<?php } else{ ?>
		<input type="checkbox" value="Allergien" name="risks[]">
	<?php } ?>

</label>

<label> Gerinnungshemmer
	<?php $risks = unserialize($results[0]->risks); ?>
	<?php if(in_array('Gerinnungshemmer', $risks)){ ?>
		<input type="checkbox" value="Gerinnungshemmer" name="risks[]" checked="checked">
	<?php } else{ ?>
		<input type="checkbox" value="Gerinnungshemmer" name="risks[]">
	<?php } ?>
</label>
</div>
<label> Infektionskrankheiten
	<input type="text" name="infektionskrankheiten" value="<?php echo $results[0]->infektionskrankheiten; ?>">
</label>

</div><!-- Krampfadern -->


<div class="foot_deformation">
<h4> Findings </h4>

<label> Hornhaut
	<?php $findings = unserialize($results[0]->findings); ?>
	<?php if(in_array('Hornhaut', $findings)){ ?>
		<input type="checkbox" value="Hornhaut" name="findings[]" checked="checked">
	<?php } else{ ?>
		<input type="checkbox" value="Hornhaut" name="findings[]">
	<?php } ?>
</label>


<label> Hallux Valgus

	<?php $findings = unserialize($results[0]->findings); ?>
	<?php if(in_array('Hallux Valgus', $findings)){ ?>
		<input type="checkbox" value="Hallux Valgus" name="findings[]" checked="checked">
	<?php } else{ ?>
		<input type="checkbox" value="Hallux Valgus" name="findings[]">
	<?php } ?>

</label>


<label> Warzen
	<?php $findings = unserialize($results[0]->findings); ?>
	<?php if(in_array('Warzen', $findings)){ ?>
		<input type="checkbox" value="Warzen" name="findings[]" checked="checked">
	<?php } else{ ?>
		<input type="checkbox" value="Warzen" name="findings[]">
	<?php } ?>
</label>

<label> Hautpilz
	<?php $findings = unserialize($results[0]->findings); ?>
	<?php if(in_array('Hautpilz', $findings)){ ?>
		<input type="checkbox" value="Hautpilz" name="findings[]" checked="checked">
	<?php } else{ ?>
		<input type="checkbox" value="Hautpilz" name="findings[]">
	<?php } ?>
</label>

<label> Wunden
	<input type="text"  name="wunden" value="<?php echo $results[0]->wunden; ?>">
</label>

<label> Huhneraugen auf Zehen
	<select name="huhneraugen_auf_zehen">

		<option <?php if($results[0]->huhneraugen_auf_zehen=="None"){ echo "selected";} ?>>None</option>
		<option <?php if($results[0]->huhneraugen_auf_zehen == "Links"){
			echo "selected";} ?>>Links</option>
		<option <?php if($results[0]->huhneraugen_auf_zehen == "Retchs"){
			echo "selected"; } ?>>Retchs</option>
		<option <?php if($results[0]->huhneraugen_auf_zehen == "Beide"){
			echo "selected"; } ?>>Beide</option>
	</select>
</label>


<label> Hammerzehen
	<select name="hammerzehen">
		<option <?php if($results[0]->hammerzehen=="None"){ echo "selected";} ?>>None</option>
		<option <?php if($results[0]->hammerzehen == "Links"){
			echo "selected";} ?>>Links</option>
		<option <?php if($results[0]->hammerzehen == "Retchs"){
			echo "selected"; } ?>>Retchs</option>
		<option <?php if($results[0]->hammerzehen == "Beide"){
			echo "selected"; } ?>>Beide</option>
	</select>
</label>

<label> Nagelpilz
	<select name="nagelpilz">
		<option <?php if($results[0]->nagelpilz=="None"){ echo "selected";} ?>>None</option>
		<option <?php if($results[0]->nagelpilz == "Links"){
			echo "selected";} ?>>Links</option>
		<option <?php if($results[0]->nagelpilz == "Retchs"){
			echo "selected"; } ?>>Retchs</option>
		<option <?php if($results[0]->nagelpilz == "Beide"){
			echo "selected"; } ?>>Beide</option>

	</select>
</label>

<label> Eingewachsene Nagel
	<select name="eingewachsene_nagel">
		<option <?php if($results[0]->eingewachsene_nagel=="None"){ echo "selected";} ?>>None</option>
		
		<option <?php if($results[0]->eingewachsene_nagel == "Links"){
			echo "selected";} ?>>Links</option>
		<option <?php if($results[0]->eingewachsene_nagel == "Retchs"){
			echo "selected"; } ?>>Retchs</option>
		<option <?php if($results[0]->eingewachsene_nagel == "Beide"){
			echo "selected"; } ?>>Beide</option>

	</select>
</label>


<label> Zustand de Nagel
	<input type="text" name="zustand_de_nagel" value="<?php echo $results[0]->zustand_de_nagel; ?>">
	<input type="hidden" name="user_id" value="<?php echo $results[0]->user_id; ?>"/>
	<input type="hidden" name="doc_id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="doctor_id" value="<?php echo $results[0]->doctor_id; ?>"/>
</label>
<input type="submit" value="Submit" class="button">
</div><!-- deformation -->

</form>

</div><!--mainClass-->

<?php 
	wp_die();
}