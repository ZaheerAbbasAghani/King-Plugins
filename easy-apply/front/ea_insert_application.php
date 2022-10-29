<?php 

add_action( 'wp_ajax_ea_insert_application', 'ea_insert_application' );
add_action( 'wp_ajax_nopriv_ea_insert_application', 'ea_insert_application' );

function ea_insert_application() {
global $wpdb; // this is how you get access to the database


global $wpdb;
$table_name = $wpdb->base_prefix.'ea_apply';

$your_name = $_POST['your_name'];
$your_phone = $_POST['your_phone'];
$your_email = $_POST['your_email'];
$additional_comments = $_POST['additional_comments'];
$appliedfor = get_the_title($_POST['id']);
$now = current_time('mysql');
	
$salary_range = get_post_meta($_POST['id'], 'salary_range', true);
$location = get_post_meta($_POST['id'], 'location', true);

$insert = $wpdb->query("INSERT INTO $table_name (your_name,your_phone, your_email,additional_comments,appliedfor,created_at) VALUES('$your_name','$your_phone','$your_email','$additional_comments','$appliedfor','$now')");

if($insert == 1){
	
    if(!empty($_FILES["upimg"]["tmp_name"])){
        $error=array();
        $extension=array("pdf","docx","doc","odt");
        $wp_upload_dir =  wp_upload_dir();
        $lastid = $wpdb->insert_id;
        $custom_upload_folder= $wp_upload_dir['basedir'].'/cv/'.$lastid;
		
        if(!is_dir($custom_upload_folder)){
        	mkdir($custom_upload_folder);
        }

        foreach($_FILES["upimg"]["tmp_name"] as $key=>$tmp_name) {
            $file_name=$_FILES["upimg"]["name"][$key];
            $file_tmp=$_FILES["upimg"]["tmp_name"][$key];
            $ext=pathinfo($file_name,PATHINFO_EXTENSION);

            if(in_array($ext,$extension)) {
                if(!file_exists($custom_upload_folder."/".$file_name)) {
                    move_uploaded_file($file_tmp=$_FILES["upimg"]["tmp_name"][$key],$custom_upload_folder."/".$file_name);
                 //   echo "SUCCESS";
                }
                else {
                    $filename=basename($file_name,$ext);
                    $newFileName=$filename.time().".".$ext;
                     move_uploaded_file($file_tmp=$_FILES["upimg"]["tmp_name"][$key],$custom_upload_folder."/".$newFileName);
                }
            }
            else {
                array_push($error,"$file_name, ");
            }
		}
		$attachment = glob($wp_upload_dir['basedir'].'/cv/'.$lastid."/*.{pdf,docx}",GLOB_BRACE);
    }
	
	$to = 'info@rosebushengineering.co.uk';
	$subject = 'Applied for job '.$appliedfor;
	$body = "<div class='email_template' style='font-family:inherit; font-size:16px; '>  Job Title: $appliedfor <br> Salary Range: $salary_range <br> Location: $location <br><hr> Date/Time: $now <hr><br> Name: $your_name <br>  Phone: $your_phone <br> Email:  $your_email <br> Comments: $additional_comments </div>";
	$headers = array('Content-Type: text/html; charset=UTF-8');

	$mail = wp_mail($to, $subject, $body, $headers, $attachment);
	
	 $custom_upload_folder= $wp_upload_dir['basedir'].'/cv/'.$id;
	 array_map('unlink', glob("$custom_upload_folder/*.*"));
     rmdir($custom_upload_folder);
	
    $response = array("message" => "Applied Successfully", "status" => 1);
	echo wp_send_json($response);

}else{
	$response = array("message" => "Something went wrong!", "status" => 0);
	echo wp_send_json($response);
}
  




	wp_die(); // this is required to terminate immediately and return a proper response
}