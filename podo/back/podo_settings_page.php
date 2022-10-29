<?php
// create custom plugin settings menu
add_action('admin_menu', 'podo_plugin_create_menu');

function podo_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Podo Settings', 'All in Podo', 'manage_options', 'podo_settings_page', 'podo_plugin_settings_page', 'dashicons-businessperson');

   // add_submenu_page( 'podo_settings_page', 'Anamnese Settings', 'Anamnese', 'manage_options', 'podo_anamnese_settings_page');

    add_submenu_page( 'podo_settings_page', 'Anamnese Settings', 'Anamnese', 'manage_options', 'podo_anamnese_settings_page', 
        'podo_anamnese_settings_page' );

    add_submenu_page( 'podo_settings_page', 'Accounting Settings','Accounting', 'manage_options', 'podo_accounting_settings_page','podo_accounting_settings_page' );

    add_submenu_page( 'podo_settings_page', 'Behandlungen Settings', 'Behandlungen', 'manage_options', 'podo_treatment_settings_page',
        'podo_treatment_settings_page' );

    add_submenu_page( 'podo_settings_page', 'Firm Settings','Firm', 'manage_options', 'podo_firm_settings_page',
        'podo_firm_settings_page' );

    add_submenu_page( 'podo_settings_page', 'Customers Settings','Customers', 'manage_options', 'podo_customers_settings_page','podo_customers_settings_page' );

    add_submenu_page( 'podo_settings_page', 'Zahlungseinstellungen Settings','Zahlungseinstellungen', 'manage_options', 'podo_documentations_settings_page','podo_documentations_settings_page' );


	//call register settings function
	//add_action( 'admin_init', 'register_podo_plugin_settings' );
    add_action( 'admin_init', 'register_podo_firm_information' );
}




function podo_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff;padding: 10px 20px;">
<h1>All in Podo - Settings </h1><hr>

<?php 

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_dokument_info';

/* Today */
$query ="SELECT sum(price) as pr, treatment_name, created_at FROM $table_name WHERE created_at >= CURDATE() GROUP BY treatment_name";
$daily = $wpdb->get_results($query, ARRAY_A);


$currency = get_option('podo_currency');
$treatment_name = array();
foreach ($daily as $d) {
   array_push($treatment_name, $d['treatment_name']);
}

$price = array();
foreach ($daily as $pr) {
   array_push($price, $pr['pr']);
}

?>
<div>
    <h3 style="background: #eee;padding: 15px 20px;box-shadow: 1px 1px 1px #ddd;">Today Revenue Chart</h3>
    <canvas id="myChart" width="200" height="100"></canvas>
</div>

<script>
const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($treatment_name) ?>,
        datasets: [{
            label: 'Today Revenue '+podo_object.podo_currency ,
            data: <?php echo json_encode($price) ?>,
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



<?php 

/* Weekly */

//$query1 ="SELECT sum(price) as weekly_revenue, treatment_name, created_at FROM $table_name WHERE WEEKOFYEAR(date(created_at))=WEEKOFYEAR(CURDATE()) AND WEEKDAY(created_at) BETWEEN 1 AND 7 GROUP BY treatment_name";
$query1 ="SELECT sum(price) as weekly_revenue, treatment_name, created_at FROM $table_name WHERE WEEKOFYEAR(created_at)=WEEKOFYEAR(NOW()) GROUP BY treatment_name";
$weely = $wpdb->get_results($query1, ARRAY_A);

//print_r($weely);

$treatment_name1 = array();
foreach ($weely as $w) {
   array_push($treatment_name1, $w['treatment_name']);
}

$wrevenue = array();
foreach ($weely as $name) {
   array_push($wrevenue, $name['weekly_revenue']);
}

?>
<div> 
    <h3 style="background: #eee;padding: 15px 20px;box-shadow: 1px 1px 1px #ddd;">Weekly Revenue Chart</h3>
    <canvas id="myChart2" width="200" height="100"></canvas>
</div>

<script>
const ctx1 = document.getElementById('myChart2').getContext('2d');
const myChart1 = new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($treatment_name1) ?>,
        datasets: [{
            label: 'Dockument',
            data: <?php echo json_encode($wrevenue) ?>,
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



<?php 

/* Montly */

$query1 ="SELECT sum(price) as monthly_revenue, treatment_name, created_at FROM $table_name WHERE date(created_at) between  DATE_FORMAT(CURDATE() ,'%Y-%m-01') AND CURDATE() GROUP BY treatment_name";
$monthly_revenue = $wpdb->get_results($query1, ARRAY_A);

//print_r($monthly_revenue);

$treatment_name2 = array();
foreach ($monthly_revenue as $m) {
   array_push($treatment_name2, $m['treatment_name']);
}

$m_revenue = array();
foreach ($monthly_revenue as $mr) {
   array_push($m_revenue, $mr['monthly_revenue']);
}

//print_r($m_revenue);

?>
<div>
    <h3 style="background: #eee;padding: 15px 20px;box-shadow: 1px 1px 1px #ddd;">Monthly Revenue Chart</h3>
    <canvas id="myChart3" width="200" height="100"></canvas>
</div>

<script>
const ctx3 =document.getElementById('myChart3').getContext('2d');
const myChart3 = new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($treatment_name2) ?>,
        datasets: [{
            label: 'Dockument',
            data: <?php echo json_encode($m_revenue) ?>,
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




</div>
<?php }

function podo_anamnese_settings_page(){
    require_once plugin_dir_path(__FILE__).'anamnese/podo_anamnese_settings_page.php';
}

function podo_treatment_settings_page(){
    require_once plugin_dir_path(__FILE__).'treatments/podo_treatment_settings_page.php';
}

function podo_customers_settings_page(){
    require_once plugin_dir_path(__FILE__).'customers/podo_customers_settings_page.php';
}

function podo_documentations_settings_page(){
    require_once plugin_dir_path(__FILE__).'documentation/documentation_settings.php';
}


function register_podo_firm_information() {
    //register our settings
    register_setting('podo-plugin-firm-group','podo_company_name');
    register_setting('podo-plugin-firm-group','podo_company_logo');
    register_setting('podo-plugin-firm-group','podo_address_number');
    register_setting('podo-plugin-firm-group','podo_zipcode');
    register_setting('podo-plugin-firm-group','podo_city');
    register_setting('podo-plugin-firm-group','podo_website');
    register_setting('podo-plugin-firm-group','podo_phone');
    register_setting('podo-plugin-firm-group','podo_mobile');
    register_setting('podo-plugin-firm-group','podo_bank_name');
    register_setting('podo-plugin-firm-group','podo_iban');
    register_setting('podo-plugin-firm-group','podo_currency');
    register_setting('podo-plugin-firm-group','podo_name_of_account');
    register_setting('podo-plugin-firm-group','podo_bic');
}




function podo_firm_settings_page(){
    require_once plugin_dir_path(__FILE__).'firm/podo_firm_info.php';
}

function podo_accounting_settings_page(){
    require_once plugin_dir_path(__FILE__).'accounting/podo_accounting_settings_page.php';
}



?>