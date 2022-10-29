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
$query1 = "SELECT * FROM $table_name WHERE id='".$id."' ";
$results = $wpdb->get_results($query1);

//$test = 'weitere_medikamente';
//print_r($results[0]->$test);


$table_name2 = $wpdb->base_prefix.'anam_fields_maker';
$query = "SELECT * FROM $table_name2 ORDER BY forder";
$fresults = $wpdb->get_results($query);



echo '<div class="mainClass">

<form method="post" action="" id="docUpdateform" enctype="multipart/form-data" name="docUpdateform"><div class="foot_deformation">';

$i = 0;
foreach ($fresults as $result) {
	
	if($result->fieldtype == 'text'){

		$string1=str_replace(' ','_',strtolower($result->label));
		$string2=str_replace(':','',$string1);
		if(!empty($results[0]->$string2)){
			echo '<label>'.$result->label.' <input type="'.$result->fieldtype.'" name="'.$result->fname.'" value="'.$results[0]->$string2.'"/></label>';
		}else{
			echo '<label>'.$result->label.' <input type="'.$result->fieldtype.'" name="'.$result->fname.'"/></label>';
		}
	}
	if($result->fieldtype == 'checkbox'){

		$string1=str_replace(' ','_',strtolower($result->label));
		$string2=str_replace(':','',$string1);

		if(!empty($results[0]->$string2)){
		?>
			<label><?php echo $result->label; ?>
			<input type="<?php echo $result->fieldtype; ?>" name="<?php echo $result->fname; ?>" <?php if($results[0]->$string2 == "on"){ echo "checked"; } ?>/></label>
		<?php } else{ ?>

			<label><?php echo $result->label; ?>
			<input type="<?php echo $result->fieldtype; ?>" name="<?php echo $result->fname; ?>"/></label>

		<?php }

	}
	if($result->fieldtype == 'textarea'){
		$string1=str_replace(' ','_',strtolower($result->label));
		$string2=str_replace(':','',$string1);
		
		if(!empty($results[0]->$string2)){
			echo '<label>'.$result->label.' <textarea name="'.$result->fname.'"/>'.$results[0]->$string2.'</textarea></label>';
		}else{
			echo '<label>'.$result->label.' <textarea name="'.$result->fname.'"/></textarea></label>';
		}
	}
	if($result->fieldtype == 'date'){
		$string1=str_replace(' ','_',strtolower($result->label));
		$string2=str_replace(':','',$string1);
		
		if(!empty($results[0]->$string2)){
			echo '<label>'.$result->label.' <input type="'.$result->fieldtype.'" name="'.$result->fname.'" value="'.$results[0]->$string2.'"/></label>';
		}else{
			echo '<label>'.$result->label.' <input type="'.$result->fieldtype.'" name="'.$result->fname.'"/></label>';
		}
	}

	$i++;
}


?>


<input type="hidden" name="user_id" value="<?php echo $results[0]->user_id; ?>" />
<input type="hidden" name="doctor_id" value="<?php echo $results[0]->doctor_id; ?>" />

<input type="hidden" name="doc_id" value="<?php echo $id; ?>" />

<input type="submit" value="Senden" class="button">

</form>

</div><!--mainClass-->

<?php 
/*
?>
<div class="mainClass">

<form method="post" action="" id="docUpdateform" enctype="multipart/form-data" name="docform">

<div class="foot_deformation">

<label> Antikoagulation:
	<input type="text" name="antikoagulation" value="<?php echo $results[0]->antikoagulation; ?>" />
</label>

<label> Weitere Medikamente:
	<input type="text" name="weitere_medikamente" value="<?php echo $results[0]->weitere_medikamente; ?>" />
</label>

<label> Allergien:
	<input type="text" name="allergien" value="<?php echo $results[0]->allergien; ?>" />
</label>

<label> Apoplexie:
	<input type="text" name="apoplexie"  value="<?php echo $results[0]->apoplexie; ?>" />
</label>

<label> Paresen:
	<input type="text" name="paresen" value="<?php echo $results[0]->paresen; ?>" />
</label>

<label> Dermatologische Erkrankungen:
	<input type="text" name="dermatologische_erkrankungen" value="<?php echo $results[0]->dermatologische_erkrankungen; ?>" />
</label>

<label> Ulcus:
	<input type="text" name="ulcus" value="<?php echo $results[0]->ulcus; ?>" />
</label>

<label> Diabetes:
	<input type="text" name="diabetes" value="<?php echo $results[0]->diabetes; ?>" />
</label>

<label> Neuropathie:
	<input type="text" name="neuropathie" value="<?php echo $results[0]->neuropathie; ?>" />
</label>

<label> Gicht:
	<input type="checkbox" name="gicht" <?php if($results[0]->gicht == "on"){ echo "checked"; } ?>/>
</label>

<label> Varizen:
	<input type="text" name="varizen" value="<?php echo $results[0]->varizen; ?>" />
</label>

<label> PAVK:
	<input type="checkbox" name="pavk" <?php if($results[0]->pavk == "on"){ echo "checked"; } ?>/>
</label>

<label> Thrombose:
	<input type="checkbox" name="thrombose" <?php if($results[0]->thrombose == "on"){ echo "checked"; } ?>/>
</label>

<label> CVI:
	<input type="checkbox" name="cvi" <?php if($results[0]->cvi == "on"){ echo "checked"; } ?>/>
</label>

<label> Phlebitis:
	<input type="checkbox" name="phlebitis" <?php if($results[0]->phlebitis == "on"){ echo "checked"; } ?>/>
</label>

</div><!-- deformation -->

<div class="foot_deformation">

<label> Oedem:
	<input type="text" name="oedem" value="<?php echo $results[0]->oedem; ?>" />
</label>

<label> Rheumatioide Arthritis RA:
	<input type="checkbox" name="rheumatioide_arthritis_ra" <?php if($results[0]->rheumatioide_arthritis_ra == "on"){ echo "checked"; } ?>/>
</label>

<label> Arthrose:
	<input type="text" name="arthrose" value="<?php echo $results[0]->arthrose; ?>" />
</label>

<label> Virale Erkrankungen:
	<input type="text" name="virale_erkrankungen" value="<?php echo $results[0]->virale_erkrankungen; ?>" />
</label>

<label> Gangrän:
	<input type="checkbox" name="gangrän" <?php if($results[0]->gangran == "on"){ echo "checked"; } ?>/>
</label>


<label> Stoffwechselerkrankungen:
	<input type="checkbox" name="stoffwechselerkrankungen" <?php if($results[0]->stoffwechselerkrankungen == "on"){ echo "checked"; } ?>/>
</label>

<label> Gefässerkrankungen:
	<input type="text" name="gefässerkrankungen" value="<?php echo $results[0]->gefasserkrankungen; ?>" />
</label>

<label> Gelenkerkrankungen:
	<input type="text" name="gelenkerkrankungen" value="<?php echo $results[0]->gelenkerkrankungen; ?>" />
</label>

<label> Operationen:
	<input type="text" name="operationen" value="<?php echo $results[0]->operationen; ?>" />
</label>

<label> Orthesen:
	<input type="checkbox" name="orthesen" <?php if($results[0]->orthesen == "on"){ echo "checked"; } ?>/>
</label>

<label> Orthonyxie:
	<input type="text" name="orthonyxie" value="<?php echo $results[0]->orthonyxie; ?>" />
</label>

<label> Nagelprotetik:
	<input type="text" name="nagelprotetik" value="<?php echo $results[0]->nagelprotetik; ?>" />
</label>

<label> Senkfuss des LW:
	<input type="checkbox" name="senkfuss_des_lw" <?php if($results[0]->senkfuss_des_lw == "on"){ echo "checked"; } ?>/>
</label>

<label> Knickung des Valgus:
	<input type="checkbox" name="knickung_des_valgus" <?php if($results[0]->knickung_des_valgus == "on"){ echo "checked"; } ?>/>
</label>

<label> Senkung des QW:
	<input type="checkbox" name="Senkung_des_qw" <?php if($results[0]->Senkung_des_qw == "on"){ echo "checked"; } ?>/>
</label>

<label> Spreizung:  HV, HR, QV:
	<input type="checkbox" name="spreizung_hv_hr_qv" <?php if($results[0]->spreizung_hv_hr_qv == "on"){ echo "checked"; } ?>/>
</label>

<label> Hohlfuss:
	<input type="checkbox" name="hohlfuss"  <?php if($results[0]->hohlfuss == "on"){ echo "checked"; } ?>/>
</label>


</div><!-- Krampfadern -->


<div class="foot_deformation">

<label> Hammerzehen:
	<input type="checkbox" name="hammerzehen" <?php if($results[0]->hammerzehen == "on"){ echo "checked"; } ?>/>
</label>

<label> Krallenzehen:
	<input type="checkbox" name="krallenzehen" <?php if($results[0]->krallenzehen == "on"){ echo "checked"; } ?>/>
</label>

<label> Beschwerden/Schmerzen:
	<input type="text" name="beschwerden_schmerzen" value="<?php echo $results[0]->beschwerden_schmerzen; ?>" />
</label>

<label> Hyperkreatose:
	<input type="checkbox" name="hyperkreatose" <?php if($results[0]->hyperkreatose == "on"){ echo "checked"; } ?>/>
</label>


<label> Rhagaden:
	<input type="checkbox" name="rhagaden" <?php if($results[0]->rhagaden == "on"){ echo "checked"; } ?>/>
</label>

<label> Malum Perforans:
	<input type="checkbox" name="malum_perforans" <?php if($results[0]->malum_perforans == "on"){ echo "checked"; } ?>/>
</label>

<label> Panaritium:
	<input type="checkbox" name="Panaritium" <?php if($results[0]->Panaritium == "on"){ echo "checked"; } ?>/>
</label>

<label> Psoriasis:
	<input type="checkbox" name="psoriasis" <?php if($results[0]->psoriasis == "on"){ echo "checked"; } ?>/>
</label> 

<label> Nävus:
	<input type="text"  name="nävus" value="<?php echo $results[0]->navus; ?>" />
</label>

<label> Clavus/Clavi:
	<input type="checkbox" name="clavus_clavi" <?php if($results[0]->clavus_clavi == "on"){ echo "checked"; } ?>/>
</label>
</label>


<label> Clavus im Nagelfalz:
	<input type="checkbox"  name="clavus_im_nagelfalz" <?php if($results[0]->clavus_im_nagelfalz == "on"){ echo "checked"; } ?>/>
</label>


<label> Clavus interdig. /subung:
	<input type="checkbox"  name="clavus_interdig_subung" <?php if($results[0]->clavus_interdig_subung == "on"){ echo "checked"; } ?>/>
</label>


<label> Verruca:
	<input type="checkbox"  name="verruca"  <?php if($results[0]->verruca == "on"){ echo "checked"; } ?>/>
</label>

<label> Bursitis:
	<input type="checkbox"  name="bursitis" <?php if($results[0]->bursitis == "on"){ echo "checked"; } ?>/>
</label>


<label> Dermatomykose:
	<input type="checkbox"  name="dermatomykose" <?php if($results[0]->dermatomykose == "on"){ echo "checked"; } ?>/>
</label>

<label> Onychokryptose:
	<input type="checkbox"  name="onychokryptose" <?php if($results[0]->onychokryptose == "on"){ echo "checked"; } ?>/>
</label>

<label> Onychoschisis:
	<input type="checkbox"  name="onychoschisis" <?php if($results[0]->onychokryptose == "on"){ echo "checked"; } ?>/>
</label>

<label> Onychomykose:
	<input type="checkbox"  name="onychomykose" <?php if($results[0]->onychomykose == "on"){ echo "checked"; } ?>/>
</label>

<label> Onychauxis:
	<input type="checkbox"  name="onychauxis" <?php if($results[0]->onychauxis == "on"){ echo "checked"; } ?>/>
</label>

<label> Onycholyse:
	<input type="checkbox"  name="onycholyse" <?php if($results[0]->onycholyse == "on"){ echo "checked"; } ?>/>
</label>

<label> Onychogryposis:
	<input type="checkbox"  name="onychogryposis" <?php if($results[0]->onycholyse == "on"){ echo "checked"; } ?>/>
</label>

<label> Onychopathie:
	<input type="checkbox"  name="onychopathie" <?php if($results[0]->onychopathie == "on"){ echo "checked"; } ?>/>
</label>

<input type="hidden" name="user_id" value="<?php echo $results[0]->user_id; ?>" />
<input type="hidden" name="doctor_id" value="<?php echo $results[0]->doctor_id; ?>" />

<input type="hidden" name="doc_id" value="<?php echo $id; ?>" />




<input type="submit" value="Senden" class="button">
</div><!-- deformation -->

</form>


</div><!--mainClass-->

*/ ?>


<?php 
	wp_die();
}