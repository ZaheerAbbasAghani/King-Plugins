<div class="wrap" style="background: #fff;padding: 10px 20px;">
<h1>Kundeneinstellungen </h1><hr>
<a href="<?php echo get_site_url().'/customer-page'; ?>" class="button button-primary" id="add_customer" target="_blank">
	Neuen Kunden hinzufügen
</a>

<?php 
global $wpdb;
$table_name = $wpdb->base_prefix.'anam_customer_info';

$query = "SELECT * FROM $table_name ORDER BY created_at DESC";
$query_results = $wpdb->get_results($query);


if(!empty($query_results)){

echo "<div class='customers_settings'>";

echo "<div class='main_filters'> 
	<div class='searches'> 
		<input type='search' name='search_filter' id='search_filter' placeholder='Kunden suchen...'/>
	</div>
	<div class='order_filters'> 
		<select id='order_filter'>
			<option disabled selected>Filtern nach</option>
			<option value='2'>Namen in aufsteigender Reihenfolge</option>
			<option value='1'>Name is ascending order</option>
			<option value='3'>Letzter Termin in absteigender Reihenfolge</option>
			<option value='4'>Letzter Termin in absteigender Reihenfolge</option>
		</select>
	</div>
</div>";


echo '<ul style="margin: 30px 0px 0px 0px;border:none;padding: 0px;">
	<li>Kunde</li>
	<li>Telefon</li>
	<li>Notiz</li>
	<li>Letzter Termin </li>
	<li>Bearbeiten</li>	
</ul>';
echo "<div class='customer_listed'>";
foreach ($query_results as $result) {
	$table_name2 = $wpdb->base_prefix.'anam_dokument_info';
	$query2 = "SELECT * FROM $table_name2 WHERE customer_id='$result->id' ORDER BY created_at DESC LIMIT 1";
	$query_results2 = $wpdb->get_results($query2);

	$query3 = "SELECT count(*) as total_docs FROM $table_name2 WHERE customer_id='$result->id' ORDER BY created_at ";
	$query_results3 = $wpdb->get_results($query3);

	echo '<ul>
			<li>'.$result->first_name.' '.$result->last_name.'</li>
			<li>'.$result->mobile_no.'</li>
			<li>'.wp_trim_words($result->important_notes, 3, '...').'</li>';
			if(!empty($query_results2[0]->created_at)){
				echo '<li>'.$query_results2[0]->created_at.'</li>';
			}else{
				echo '<li> Nicht gefunden! </li>';
			}
			
	echo '<li><a href="#" class="edit_customer_dashboard button button-primary" data-id="'.$result->id.'"> Bearbeiten </a></li>	
			<div class="extended_bar" style="display:none;">
				<div class="appointments">
					<p> Alle Termine: '.$query_results3[0]->total_docs.' </p>';
				if(!empty($query_results2[0]->created_at)){
					'<p> Letzter Termin: '.$query_results2[0]->created_at.'</p>';
				}else{
					'<p> Letzter Termin: Nicht gefunden! </p>';
				}
	echo '<p> E-Mail: '.$result->email_address.'</p>
				</div>
				<div class="appointments">
					<p> Notiz: '.$result->important_notes.'</p>
				</div>
			</div>
		</ul>';
}

echo "</div>";

echo "</div>";


}else{
	echo "<h3> No Kunde found!  </h3>";
}

?>


</div>