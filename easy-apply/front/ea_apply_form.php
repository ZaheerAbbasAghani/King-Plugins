<?php 

add_action( 'wp_ajax_ea_apply_form', 'ea_apply_form' );
add_action( 'wp_ajax_nopriv_ea_apply_form', 'ea_apply_form' );

function ea_apply_form() {
global $wpdb; // this is how you get access to the database


$id = $_POST['id'];

$cv = plugin_dir_url( __FILE__ ) . 'images/Attach.png'; 
$submit = plugin_dir_url( __FILE__ ) . 'images/Submit.png'; 


echo "<div class='applyformWrap'>

	<form method='post' action='' name='submit_application' id='submit_application'> 
		<ul>
			<li class='heading'> Direct Application for:</li>
			<li class='heading'> QHSE Auditor </li>

			<li class='fields' style='background:#cfd5ea;'>Name:</li>
			<li class='fields' style='background:#cfd5ea;'> <input type='text' name='your_name' id='your_name' placeholder='John Smith' required> </li>

			<li class='fields' style='background:#e9ebf5;'>Contact Number:</li>
			<li class='fields' style='background:#e9ebf5;'> <input type='phone' name='your_phone' id='your_phone' placeholder='07123 456 789' required> </li>

			<li class='fields' style='background:#cfd5ea;'>Contact Email:</li>
			<li class='fields' style='background:#cfd5ea;'> <input type='email' name='your_email' id='your_email' placeholder='somebody@outlook.com' required> </li>

			<li class='fields' style='background:#e9ebf5;height:110px;'>Additional Comments:</li>
			<li class='fields' style='background:#e9ebf5;height:auto !important;padding:10px 12px;'> <textarea  name='additional_comments' id='additional_comments' placeholder='(optional)' rows='2' cols='5' style='height:auto !important;'></textarea> </li>
		</ul>

		<input type='file' name='attach_cv' id='attach_cv' style='display:none'>

		<img src='".$cv."' id='attach_cv_img'>

		<input type='submit' value='Submit' id='subAppli' style='background-image:url(".$submit.")'>
	</form>
</div>";


	wp_die(); // this is required to terminate immediately and return a proper response
}