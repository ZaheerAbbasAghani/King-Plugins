<div class="wrap" style="background: #fff;padding: 10px 20px;">
<h1>Anamnese Einstellungen </h1><hr>
<a href="#" class="button button-primary" id="add_sickness">
	Eintrag hinzufügen	
</a>

<div class="podosearchBox">
	<input type="search" name="anam_search" id="anam_search" placeholder="Suche anamnese..."> 
</div>

<?php 
global $wpdb;
$table_name = $wpdb->base_prefix.'anam_fields_maker';

$query = "SELECT * FROM $table_name ORDER BY forder";
$query_results = $wpdb->get_results($query);

if(!empty($query_results)){
	echo "<div class='anamnese_settings'>";
	foreach ($query_results as $result) {
			$string = str_replace(' ', '', strtolower($result->label));
			echo "<ul id='".$result->id."'>
				<li>".$result->label." </li>
				<li> <span>Art des Eintrags: </span>".$result->fieldtype." </li>
				<li><a href='#' class='edit_field button button-default' data-id='".$result->id."'> Bearbeiten </a></li>
				<li><a href='#' class='delete_field button button-default' data-id='".$result->id."' data-column='".$string."'> Löschen </a></li>
			</ul>";

	}
	echo "</div>";
}else{
	echo "<h3> Keine hinzufügen gefunden! </h3>";
}

?>


</div>