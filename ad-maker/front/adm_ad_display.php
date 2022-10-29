<?php 

function validate_mobile($mobile)
{
    return preg_match('/^[6-9]\d{9}$/', $mobile);
}

function adm_ad_display($table){

global $wpdb;
$Columntable = $wpdb->base_prefix.'adm_fields_table';
$dataTable = $wpdb->base_prefix.'adm_submitted_data_table';

$cquery = "SELECT * FROM $Columntable ORDER BY position ASC";
$column_results = $wpdb->get_results($cquery);



$allColumns = array();
foreach ($column_results as $columns) {
	if(!empty($columns->label)){
		array_push($allColumns,str_replace(' ', '_',  strtolower($columns->label)));
	}
}


$query = "SELECT * FROM $dataTable ORDER BY id DESC";
$data_results = $wpdb->get_results($query);

$table .= '<div style="text-align:center;"><h2> '.get_option('form_headings').' </h2>';
$table .= '<p style="font-size:17px;"> '.get_option('form_description').' </p></div>';

$table .= '<table id="example" class="display" style="width:100%">
	<thead>
	<tr>';
	foreach ($column_results as $column) {
			if(!empty($column->label)){
				$table .=  "<th>".$column->label."</th>";
			}
	}
	    
$table .='</tr>
	</thead>
	<tbody>';
		$i=0;
		
		$total_coumns =  count($allColumns);
		foreach ($data_results as $results) {

			$table .=  "<tr>";
			$k=0;
			foreach (unserialize($results->formData) as $key => $value) {

					if(in_array($key, $allColumns)){

							if(is_array(maybe_unserialize($value))){

								$table .=  "<td>";
								$j=0;
								foreach (maybe_unserialize($value) as $v) {
									if($j == 0){
										$table .= $v;
									}else{
										$table .= ", ".$v;
									}
									$j++;
								}
								$table .=  "</td>";
								
							}else{

								if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
	    							$table .=  "<td><a href='mailto:".$value."' > Click to email </a></td>";
	    						}else{

	    							$justNums = preg_replace("/[^0-9]/", '', $value);
	    							if(strlen($justNums) >= 11 && !empty($value)) {
									   $table .=  "<td><a href='tel:".$value."' > Click to phone </a></td>";
									} else {
										if(!empty($value)){
									    	$table .=  "<td>".$value."</td>";
									    	
									    }else{
									    	$table .=  "<td> - </td>";

									    	/*if($j == $total_coumns){
									    		$table .=  "<td> - </td>";
									    	}*/
									    }
									}
	    						}
								
							}
						$k++;
					}
				
			}

			$table .=  "</tr>";

			$i++;
		}
	$table .='</tbody>
	<tfoot>
	<tr>';
	foreach ($column_results as $column) {
			if(!empty($column->label)){
				$table .=  "<th>".$column->label."</th>";
			}
	}
	$table .='</tr>
	</tfoot>
</table>';


return $table;
}

add_shortcode("ad_display", "adm_ad_display");