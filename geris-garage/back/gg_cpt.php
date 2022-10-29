<?php

/*
* Creating a function to create our CPT
*/
 
function gg_custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Products', 'Post Type General Name', 'geris-garage' ),
        'singular_name'       => _x( 'Product', 'Post Type Singular Name', 'geris-garage' ),
        'menu_name'           => __( 'Products', 'geris-garage' ),
        'parent_item_colon'   => __( 'Parent Product', 'geris-garage' ),
        'all_items'           => __( 'All Products', 'geris-garage' ),
        'view_item'           => __( 'View Product', 'geris-garage' ),
        'add_new_item'        => __( 'Add New Product', 'geris-garage' ),
        'add_new'             => __( 'Add New', 'geris-garage' ),
        'edit_item'           => __( 'Edit Product', 'geris-garage' ),
        'update_item'         => __( 'Update Product', 'geris-garage' ),
        'search_items'        => __( 'Search Product', 'geris-garage' ),
        'not_found'           => __( 'Not Found', 'geris-garage' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'geris-garage' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'products', 'geris-garage' ),
        'description'         => __( 'Product news and reviews', 'geris-garage' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'    => array( 'title', 'author', 'thumbnail','revisions'),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'genres' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 20,
        'menu_icon'   => 'dashicons-cart',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'products', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 

add_action( 'init', 'gg_custom_post_type', 0 );


function product_add_meta_box() {
//this will add the metabox for the member post type
$screens = array( 'products' );

foreach ( $screens as $screen ) {

    add_meta_box(
        'product_sectionid',
        __( 'Product Details', 'geris-garage' ),
        'gg_product_meta_box_callback',
        $screen
    );
 }
}
add_action( 'add_meta_boxes', 'product_add_meta_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function gg_product_meta_box_callback( $post ) {

// Add a nonce field so we can check for it later.
wp_nonce_field( 'gg_product_save_meta_box_data', 'product_meta_box_nonce' );

/*
 * Use get_post_meta() to retrieve an existing value
 * from the database and use the value for the form.
 */
$value = get_post_meta( $post->ID, '_product_price', true );

echo '<label for="product_price">';
_e( 'Price', 'geris-garage' );
echo '</label> ';
echo '<input type="text" id="product_price" name="product_price" value="' . esc_attr( $value ) . '" size="25" />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
 function gg_product_save_meta_box_data( $post_id ) {

 if ( ! isset( $_POST['product_meta_box_nonce'] ) ) {
    return;
 }

 if ( ! wp_verify_nonce( $_POST['product_meta_box_nonce'], 'gg_product_save_meta_box_data' ) ) {
    return;
 }

 if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
 }

 // Check the user's permissions.
 if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

 } else {

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
 }

 if ( ! isset( $_POST['product_price'] ) ) {
    return;
 }

 $my_data = sanitize_text_field( $_POST['product_price'] );

 update_post_meta( $post_id, '_product_price', $my_data );
}
add_action( 'save_post', 'gg_product_save_meta_box_data' );




/**
 * Adds a submenu page under a custom post type parent.
 */
add_action( 'admin_menu', 'gg_settings_page' );

function gg_settings_page() {
    add_submenu_page(
        'edit.php?post_type=products',
        __( 'History', 'textdomain' ),
        __( 'History', 'textdomain' ),
        'manage_options',
        'gg_history',
        'gg_history_page_callback'
    );
}
 
/**
 * Display callback for the submenu page.
 */
function gg_history_page_callback() { 
    ?>
    <div class="wrap" style="background: #fff; padding: 10px 20px;">
        <h1><?php _e( 'User Purchase History', 'textdomain' ); ?></h1><hr>
       
<?php 

$sum = array();

    global $wpdb;
    $table_name = $wpdb->base_prefix.'gg_history';
    //$user_id = get_current_user_id();
    $query = "SELECT DISTINCT user_id FROM $table_name";
    $results = $wpdb->get_results($query);

     if(!empty($results)){


    echo '<div id="accordion">';
    $j=0;
        foreach ($results as $result) {
         $user = get_user_by('ID', $result->user_id);
         echo '<h3 data-id="'.$result->user_id.'">'.strtoupper($user->display_name).'</h3>
        <div>';
           

    $query = "SELECT * FROM $table_name WHERE user_id='$result->user_id'";
    $query_results = $wpdb->get_results($query);

   
?>


<table id="gg_history_table_<?php echo $j; ?>" class="display" style="width:100%;text-align: center;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
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
                echo "<tr data-id='".$query->product_id."' data-status='".$query->status."'><td>".$i."</td>";
                echo "<td>".$query->user_email."</td>";
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
            <th>Email</th>
            <th>Product</th>
            <th>Purchases</th>
            <th>Status</th>
            <th>Date/Time</th>
        </tr>
    </tfoot>
</table>



<?php 
   echo '<h1 style="text-align: center;font-size: 70px;font-weight:bold;"> <span style="text-align: center;font-size: 20px;display:block;"> Total Purchase:  </span>'.$sum.'</h1>'."<a href='#' class='gg_reset_now'> Reset Now </a>";

        echo '</div>';

        $j++;
        }
    echo '</div>';

}
else{
    echo "<b> No Record Found! </b>";
}
?>

<?php 
/* 
    Chart Total Price By Product Name 
*/
// List of Distinct Products
$query_c1 = "SELECT product_id, sum(user_purchases) as up FROM $table_name WHERE status=1 GROUP BY user_purchases";
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
    type: 'bar',
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
$query ="SELECT COUNT(*) as entry,DATE(created_date) as entry_date FROM $table_name GROUP BY DATE(created_date)";
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
    type: 'bar',
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
$query1 ="SELECT COUNT(*) as entry,YEAR(created_date) = $d , MONTH(created_date) as entry_month FROM $table_name GROUP BY MONTH(created_date)";
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
    type: 'bar',
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


$query2 ="SELECT COUNT(*) as entry, YEAR(created_date) as entry_date FROM $table_name GROUP BY YEAR(created_date)";
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
    type: 'bar',
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

    </div>
    <?php
}