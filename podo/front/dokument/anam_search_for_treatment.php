<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_anam_search_for_treatment', 'anam_search_for_treatment' );
add_action( 'wp_ajax_anam_search_for_treatment', 'anam_search_for_treatment' );
function anam_search_for_treatment() {
	

global $wpdb;
$table_name = $wpdb->base_prefix.'anam_treatments_list';


//print_r($_POST["keyword"]);

if(!empty($_POST["keyword"])) {
$query ="SELECT * FROM $table_name WHERE name like '" . $_POST["keyword"] . "%' ORDER BY name LIMIT 0,6";
$results = $wpdb->get_results($query);

    if(!empty($results)) {
        echo '<ul id="treatment-list">';
            foreach($results as $result) { ?>
                <li onClick="selectCountry('<?php echo $result->name ?>');" data-id="<?php echo $result->id; ?>" class="treatment-item">
                    <?php echo $result->name; ?>
                </li>
            <?php }  ?>
        </ul>


    <script type="text/javascript">
        function selectCountry(val) {
            jQuery("#search_treatments").val(val);
            jQuery(".autocomplete-box").hide();
        }
    </script>


<?php 
    }

}


	wp_die();
}