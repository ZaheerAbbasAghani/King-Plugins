<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_anam_create_new_document', 'anam_create_new_document' );
add_action( 'wp_ajax_anam_create_new_document', 'anam_create_new_document' );
function anam_create_new_document() {
	
global $wpdb;
$table_name = $wpdb->base_prefix.'anam_customer_info';
$c_id = $_POST['id'];
$d_id = get_current_user_id();


$table_name2 = $wpdb->base_prefix.'anam_fields_maker';

$query = "SELECT * FROM $table_name2 ORDER BY forder";
$results = $wpdb->get_results($query);

//print_r($results);


echo '<div class="mainClass">

<form method="post" action="" id="docform" enctype="multipart/form-data" name="docform"><div class="foot_deformation">';

$i = 0;
foreach ($results as $result) {
	
	if($result->fieldtype == 'text'){
		$str = str_replace('_',' ',ucwords($result->label));
		echo '<label>'.$str.' <input type="'.$result->fieldtype.'" name="'.$result->fname.'"/></label>';
	}
	if($result->fieldtype == 'checkbox'){
		$str = str_replace('_',' ',ucwords($result->label));
		echo '<label>'.$str.' <input type="'.$result->fieldtype.'" name="'.$result->fname.'"/></label>';
	}
	if($result->fieldtype == 'textarea'){
		$str = str_replace('_',' ',ucwords($result->label));
		echo '<label>'.$str.' <textarea name="'.$result->fname.'"/></textarea></label>';
	}
	if($result->fieldtype == 'date'){
		$str = str_replace('_',' ',ucwords($result->label));
		echo '<label>'.$str.' <input type="'.$result->fieldtype.'" name="'.$result->fname.'"/></label>';
	}

	$i++;
}

echo '<input type="hidden" name="user_id" value="'.$c_id.'"/>
<input type="hidden" name="doctor_id" value="'.$d_id.'"/>

<input type="submit" value="Senden" class="button"> </div></form> </div><!--mainClass-->';
	wp_die();
}