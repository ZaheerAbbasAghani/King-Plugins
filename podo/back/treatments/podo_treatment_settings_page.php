<div class="wrap" style="background: #fff;padding: 10px 20px;">
<h1>Behandlungseinstellungen  </h1><hr>
<a href="#" class="button button-primary" id="add_treatment" onclick="jQuery('#extruderLeft').openMbExtruder(true);jQuery('#extruderLeft').openPanels()">
	Behandlung hinzufügen
</a>
<select id="order_by" style="width: 20%;float: right;">
	<option disabled selected>Sortieren nach</option>
	<option value="1">Behandlung</option>
	<option value="2">Preis</option>
	<option value="3">Dauer</option>
</select>
<div id="extruderLeft" class="{url:'<?php echo plugins_url('podo_create_treatment_popup.php', __FILE__); ?>'}"></div>


<div id="extruderLeft2" class="{url:'<?php echo plugins_url('podo_create_treatment_popup.php', __FILE__); ?>'}"></div>


<?php 
global $wpdb;
$table_name = $wpdb->base_prefix.'anam_treatments_list';

$query = "SELECT * FROM $table_name ORDER BY created_at DESC";
$query_results = $wpdb->get_results($query);
$currency = get_option('podo_currency');

if(!empty($query_results)){

echo "<div class='treatments_settings'>";

foreach ($query_results as $result) { ?>
		<ul class='box edit_treatment' data-id='<?php echo $result->id; ?>' onclick="jQuery('#extruderLeft2').openMbExtruder(true);jQuery('#extruderLeft2').openPanels()">
			<span class="dashicons dashicons-admin-post"></span>
			<li><?php echo $result->name; ?></li>
			<li> Dauer: <?php echo $result->duration; ?></li>
			<li> Kosten: <?php echo $result->price.' '.$currency; ?> </li>
		</ul>
<?php 
}

echo "</div>";

}else{
	echo "<h3> Keine Behandlungen gefunden!  </h3>";
}

?>


</div>