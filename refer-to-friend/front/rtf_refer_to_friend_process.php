<?php 
if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

add_action( 'wp_ajax_nopriv_rtf_create_new_entry', 'rtf_create_new_entry' );
add_action( 'wp_ajax_rtf_create_new_entry', 'rtf_create_new_entry' );
function rtf_create_new_entry() {
	
global $wpdb;

$table_name = $wpdb->base_prefix.'rtf_fields_maker';

$query = "SELECT * FROM $table_name ORDER BY forder";
$results = $wpdb->get_results($query);

$title = esc_attr(get_option('rtf_form_title') ); 
$bgColor = esc_attr(get_option('rtf_background_color'));
$txtColor = esc_attr(get_option('rtf_text_color'));
$opacity = esc_attr(get_option('rtf_opacity'));
$rtf_field_text_color = get_option('rtf_field_text_color');
$height = esc_attr(get_option('rtf_form_field_height'));
$width = esc_attr(get_option('rtf_form_field_width'));
$fontSize = esc_attr(get_option('rtf_form_font_size' ));

$rtf_button_text = esc_attr(get_option('rtf_button_text' ));
$rtf_button_background = esc_attr(get_option('rtf_button_background' ));
$rtf_border_radius = esc_attr(get_option('rtf_border_radius' ));


echo '<div class="mainClass">
<form method="post" action="" id="refer_form"> 
<div class="foot_deformation"><h3 style="color:#fff;">'.$title.'</h3> ';


if(!empty($results)){

	$i = 0;
	foreach ($results as $result) {
		
		if($result->fieldtype == 'text'){
			echo '<label>'.ucwords($result->label).' <input type="'.$result->fieldtype.'" name="'.$result->fname.'" placeholder="'.$result->fplaceholder.'" required/></label>';
		}

		if($result->fieldtype == 'email'){
			echo '<label>'.ucwords($result->label).' <input type="'.$result->fieldtype.'" name="'.$result->fname.'" placeholder="'.$result->fplaceholder.'" required/></label>';
		}
		if($result->fieldtype == 'checkbox'){
			echo '<label><input type="'.$result->fieldtype.'" name="'.$result->fname.'" placeholder="'.$result->fplaceholder.' " required/>'.ucwords($result->label).' </label>';
		}
		if($result->fieldtype == 'textarea'){
			echo '<label>'.ucwords($result->label).' <textarea name="'.$result->fname.'" placeholder="'.$result->fplaceholder.'" required/></textarea></label>';
		}
		if($result->fieldtype == 'date'){
			echo '<label>'.ucwords($result->label).' <input type="'.$result->fieldtype.'" name="'.$result->fname.'" placeholder="'.$result->fplaceholder.' " required /></label>';
		}

		if($result->fieldtype == 'select'){
			echo '<label>'.ucwords($result->label);
			echo '<select name="'.$result->fname.'" required>';

			$fvalue = unserialize($result->fvalue);
			echo "<option disabled selected>".$result->fplaceholder."</option>";
			foreach ($fvalue as $value) {
				echo "<option>".$value."</option>";
			}

			echo "</select></label>";
		}

		$i++;
	}

	//$titles = str_replace(' ', '%20', get_the_title($_POST['page_id']));

	echo '<br>

	<input type="hidden" id="page_id_field" name="page_id_field" value="'.urlencode(get_the_title($_POST['page_id'])).'">
	<input type="hidden" id="page_url" name="page_url" value="'.(get_the_permalink($_POST['page_id'])).'">
	<input type="submit" value="'.$rtf_button_text.'" class="button"> </div></form> </div><!--mainClass-->';

	echo "<style>
	.swal2-popup{
		background-color:".$bgColor.";
		opacity:".$opacity.";
	}

	.swal2-html-container form label{
		color:".$txtColor.";
	}

	.swal2-html-container input[type='email'], input[type='text'], textarea, select {
	    color:".$rtf_field_text_color." !important;
	    height:".$height.";
	    width:".$width.";
	    font-size:".$fontSize." !important;
	}

	.swal2-html-container input[type='email']:focus, input[type='text']:focus, textarea:focus, select:focus {
	    color:".$rtf_field_text_color." !important;
	}

	#refer_form input[type='submit']{
		background-color:".$rtf_button_background.";
		border-radius:".$rtf_border_radius.";

	}


	</style>";
}else{
	echo "<h4> Please create form fields first. Thanks </h4>";
}


	wp_die();
}