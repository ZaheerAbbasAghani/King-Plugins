<?php
/**
 * Add a new Profile tab
 *
 * @param array $tabs
 * @return array
 */
function gg_um_historytab_add_tab( $tabs ) {

	$tabs[ 'gg_purchase_history' ] = array(
		'name'   => 'Purchase History',
		'icon'   => 'um-faicon-history',
		'custom' => true
	);

	UM()->options()->options[ 'profile_tab_' . 'gg_purchase_history' ] = true;

	return $tabs;
}
add_filter( 'um_profile_tabs', 'gg_um_historytab_add_tab', 1000 );

/**
 * Render tab content
 *
 * @param array $args
 */
function um_profile_content_gg_purchase_history_default( $args ) {
	/* START. You can paste your content here, it's just an example. */
	
	
		global $wpdb;
    $table_name = $wpdb->base_prefix.'gg_history';
    $user_id = get_current_user_id();
	$query = "SELECT * FROM $table_name WHERE user_id='$user_id'";
	$query_results = $wpdb->get_results($query);

?>

<table id="gg_history_table" class="display" style="width:100%;text-align: center;">
        <thead>
            <tr>
                <th>ID</th>

                <th>Product</th>
                <th>Purchases</th>
                <th>Status</th>
                <th>Date/Time</th>
            </tr>
        </thead>
        <tbody>
            	
            	<?php 
            	$i = 1;
                $sum = 0;
            		foreach ($query_results  as $query) {
            		echo "<tr><td>".$i."</td>";
            		echo "<td>".get_the_title($query->product_id)."</td>";
            		echo "<td>".$query->user_purchases."</td>";

                   if($query->status == 0){
                        $v = str_replace("€", "", $query->user_purchases);
                        $sum+= $v;
                        echo "<td><span style='background:#ff0000c2;padding: 6px 15px;color:#fff;border-radius: 20em;font-size: 14px;'>Not Paid</span></td>";
                    }else{
                        echo "<td><span style='background:green; padding: 6px 15px;color: #fff;border-radius: 20em;font-size: 14px;'>Paid</span></td>";
                    }
                    
            		echo "<td>".$query->created_date."</td></tr>";
            		$i++;
            	} ?>
               
            
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>

                <th>Product</th>
                <th>Purchases</th>
                <th>Status</th>
                <th>Date/Time</th>
            </tr>
        </tfoot>
    </table>

<?php 

    echo '<h1 style="text-align: center;font-size: 70px;"> <span style="text-align: center;font-size: 20px;display:block;"> Total Purchase:  </span>'.$sum.'</h1>';
	//echo $sum;

?>


<?php 
/* 
    Chart Total Price By Product Name 
*/
// List of Distinct Products
$query_c1 = "SELECT product_id, sum(user_purchases) as up FROM $table_name WHERE status=1 AND user_id=$user_id GROUP BY user_purchases";
$results_c1 = $wpdb->get_results($query_c1,ARRAY_A);

if(!empty($results_c1)){

    // Diffrent Product IDs
    $rc1_array = array();
    foreach ($results_c1 as $rc1) {
        array_push($rc1_array,get_the_title($rc1['product_id']));
    }

    // Diffrent Product Sums
    $rc2_array = array();
    foreach ($results_c1 as $rc1) {
        array_push($rc2_array,$rc1['up']);
    }

?>
<h3 style="margin-top: 50px;">Chart Per Product Purchase</h3><hr>
<div style="width: 100%;">
    <canvas id="myChart" width="400" height="150"></canvas>
</div>

<script type="text/javascript">
    
var ctx = document.getElementById('myChart');
var myChart = new Chart(ctx, {
    type: 'bar', responsive: true,
    data: {
        labels: <?php echo json_encode($rc1_array); ?>,
        datasets: [{
            label: '# Total Purchases',
            data: <?php echo json_encode($rc2_array); ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<?php } // check if not empty ?>


<div class="chartsList" style="clear: both;height: auto; overflow: hidden;">

<div class="day_by_day" style="float: left; width: 50%;">
<?php 

// Fetching record from database day by day
$query ="SELECT COUNT(*) as entry,DATE(created_date) as entry_date FROM $table_name WHERE status=1 AND user_id='$user_id' GROUP BY DATE(created_date) ";
$results = $wpdb->get_results($query);

if(!empty($results)){

$labels = array();
$values = array();
foreach ($results as $key=>$value ) {
  array_push($labels,"'".$value->entry_date."'");
  array_push($values,$value->entry);
}
$labels_set = implode(", ",$labels);
$values_set = implode(", ",$values);
?>

<h3 style="clear: both;">Day By Day Purchase</h3><hr>
    <div style="width: 500px;">
        <canvas id="myChart1" width="400" height="200"></canvas>
    </div>


<script type="text/javascript">

var ctx = document.getElementById('myChart1').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar', responsive: true,
    data: {
        labels: [<?php echo $labels_set; ?>],
        datasets: [{
            label: 'Today Purchases',
            data: [<?php echo $values_set; ?>],
            fillColor: 'rgba(54, 162, 235, 0.2)',
            backgroundColor: "#33AEEF",
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});

</script>


<?php } // end day by day ?>

</div><!-- end daybyday -->


<div class="month_by_month" style="float: left; width: 50%;">

<?php 


// Fetching record from database month by month
global $wpdb; 
$table_name1 = $wpdb->base_prefix.'db7_forms';
$d = Date("Y");
$query1 ="SELECT COUNT(*) as entry,YEAR(created_date) = $d , MONTH(created_date) as entry_month FROM $table_name WHERE status=1 AND user_id='$user_id' GROUP BY MONTH(created_date) ";
$results1 = $wpdb->get_results($query1);


$labels1 = array();
$values1 = array();
foreach ($results1 as $key=>$value ) {

  array_push($labels1,"'".date('F', mktime(0, 0, 0, $value->entry_month, 10))."'");
  array_push($values1,$value->entry);
}
$labels_set1 = implode(", ",$labels1);
$values_set1 = implode(", ",$values1);
?>

  <h3>Month By Month Purchase</h3><hr>
    <div style="width: 500px;">
        <canvas id="myChart2" width="400" height="200"></canvas>
    </div>


<script type="text/javascript">

var ctx = document.getElementById('myChart2').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar', responsive: true,
    data: {
        labels: [<?php echo $labels_set1; ?>],
        datasets: [{
            label: 'This Month Purchases',
            data: [<?php echo $values_set1; ?>],
            fillColor: 'rgba(54, 162, 235, 0.2)',
            backgroundColor: "#33AEEF",
        borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});

</script>

</div><!-- month_by_month -->



<div class="year_by_year" style="float: left; width: 50%;">

<?php 

// Fetching record from database year by year


$query2 ="SELECT COUNT(*) as entry, YEAR(created_date) as entry_date FROM $table_name WHERE status=1 AND user_id='$user_id' GROUP BY YEAR(created_date) ";
$results2 = $wpdb->get_results($query2);


$labels2 = array();
$values2 = array();
foreach ($results2 as $key=>$value ) {
  array_push($labels2,"'".$value->entry_date."'");
  array_push($values2,$value->entry);
}
$labels_set2 = implode(", ",$labels2);
$values_set2 = implode(", ",$values2);
?>


  <h3>Year By Year Purchases</h3><hr>
<div style="width: 500px;">
    <canvas id="myChart3" width="400" height="200"></canvas>
</div>


<script type="text/javascript">

var ctx = document.getElementById('myChart3').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar', responsive: true,
    data: {
        labels: [<?php echo $labels_set2; ?>],
        datasets: [{
            label: 'This Year Purchases',
            data: [<?php echo $values_set1; ?>],
            fillColor: 'rgba(54, 162, 235, 0.2)',
            backgroundColor: "#33AEEF",
        borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});

</script>

</div><!-- yearByYear -->


</div><!-- chartList -->



<?php 
	/* END. You can paste your content here, it's just an example. */
}
add_action( 'um_profile_content_gg_purchase_history_default', 'um_profile_content_gg_purchase_history_default' );