<?php 

function rss_frontend_tables($scoring){

$data = get_transient( 'rss_divisions' );

	$scoring .= "<div class='rss_wrap_data'>";
        $data = get_transient( 'rss_divisions' );
        $i=1;

        foreach ($data as $value) {  	
		
		$scoring .= '<table id="example" class="display" style="width:100%">
        <thead>
         	<tr>
         		<td colspan="9" style="text-align:left;">'.$value->name.'</td>
         	</tr>
            <tr>
                <th>Position</th>
                <th>Car</th>
                <th>Driver</th>
                <th>Home Town</th>';

            if(get_option("rss_number_of_top1") == 1){
                $scoring .= ' <th>Top 1</th>';
            }

            if(get_option("rss_number_of_top3") == 1){
                $scoring .= ' <th>Top 3</th>';
            }

            if(get_option("rss_number_of_top5") == 1){
                $scoring .= ' <th>Top 5</th>';
            }

        	$scoring .= '<th>Points</th>';
            
            if(get_option('rss_point_difference') == 1){
            	$scoring .= '<th>Diff</th>';
            }

            $scoring .= '</tr>
        </thead>
        <tbody>';

        foreach ($value->myDrivers as $v) {
        	
        	$scoring.=' <tr>
		    <td>'.$i.'</td>
		    <td>'.$v->carNumber.'</td>
		    <td>'.$v->fullName.'</td>
		    <td>'.$v->homeTown.'</td>';
		    if(get_option("rss_number_of_top1") == 1){
		    	$scoring .= '<td>'.$v->top1.'</td>';
		    }

		    if(get_option("rss_number_of_top3") == 1){
		    	$scoring .= '<td>'.$v->top3.'</td>';
		    }

		    if(get_option("rss_number_of_top5") == 1){
		    	$scoring .= '<td>'.$v->top5.'</td>';
		    }

		    $scoring .= '<td>'.$v->myTotalPoints.'</td>';

		    if(get_option('rss_point_difference') == 1){
            	$scoring .= '<td>'.$value->leader->myTotalPoints - $v->myTotalPoints.'</td>';
            }
            
		    

			$scoring .= '</tr>';
			 $i++;
        }

        $scoring.='</tbody>
        <tfoot>
            <tr>
                <th>Position</th>
                <th>Car</th>
                <th>Driver</th>
                <th>Home Town</th>';
	            if(get_option("rss_number_of_top1") == 1){
	                $scoring .= ' <th>Top 1</th>';
	            }

	            if(get_option("rss_number_of_top3") == 1){
	                $scoring .= ' <th>Top 3</th>';
	            }

	            if(get_option("rss_number_of_top5") == 1){
	                $scoring .= ' <th>Top 5</th>';
	            }
            
            $scoring .= '<th>Points</th>';
            
            if(get_option('rss_point_difference') == 1){
            	$scoring .= '<th>Diff</th>';
            }

            $scoring .= '</tr>
        </tfoot>
    	</table>';

        }    
         
    $scoring .= "</div>";

return $scoring;
}

add_shortcode("racing-scoring-status", "rss_frontend_tables");