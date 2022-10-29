<?php
// create custom plugin settings menu
add_action('admin_menu', 'pi_plugin_create_menu');

function pi_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Program Importer','Program Importer Settings','manage_options','program_importer_settings','pi_plugin_settings_page' , 'dashicons-database-import',30);

}



//print_r($t);

function pi_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff; padding: 10px 20px;">
<h1>Program Importer Settings</h1><hr>

<div class="container">
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label>Upload CSV File</label>
            <input type="file" name="file" class="form-control">
        </div>
        <div class="form-group"><br>
            <button type="submit" name="Submit" class="button button-primary">Upload</button>
        </div>
    </form>
</div>

<br>
<hr>

<?php 

if(isset($_POST['Submit'])){

   $file = $_FILES["file"]["tmp_name"];
     $file_open = fopen($file,"r");
    $html="<table border='1'>";
      $programs = array();
     while(($csv = fgetcsv($file_open, 1000, ",")) !== false)
    {
    
		$html.="<tr>";
		$timezone = $csv[1];
		$date = $csv[2];
		$fdate= date_i18n('F j, Y',strtotime($date));
		$p_start_time=$csv[3];
		$p_end_time = $csv[4];
		$title = $csv[5];
		$code = $csv[7];

		$fstart_time = strtotime($p_start_time);
		$fend_time = strtotime($p_end_time);

		$html.="<td>".$timezone."</td>";
		$html.="<td>".$fdate."</td>";
		$html.="<td>".$p_start_time."</td>";
		$html.="<td>".$p_end_time."</td>";
		$html.="<td>".$title."</td>";
		$html.="<td>".$code."</td>";

		// Getting post title by code/number
		$args = array(
		'posts_per_page'   => -1,
		'post_type'        => 'programs',
		'meta_key'         => 'code_number',
		'meta_value'       => $code,
		);
		$query = new WP_Query($args);


		if($query->have_posts()): while($query->have_posts()): $query->the_post();
		$html.="<td>".get_the_title()."</td>";
		$cast = get_post_meta(get_the_id(), 'cast', true );
		$synopsis = get_post_meta(get_the_id(), 'synopsis', true );
			$uplolad_url = wp_upload_dir();
			$image =  $uplolad_url['baseurl'].'/posters/'.$code.'.jpg';


		$programs[] = array("_programme_id"=>$code,"_time_start"=> $fstart_time,"_time_end" => $fstart_time + $fend_time,"_name" => $code,"_subtitle"=>$cast, "_content"=>$synopsis,"post_title"=>$fdate, "timezone" => $timezone, "_image" => $image);


		endwhile;
		endif;

		$html.="</tr>";

      } //endwhile

   $html.="</table>";

$i=0;
foreach ($programs as $program) {
    //echo $program['timezone'].'<br>';
  if ( 0 === post_exists($program['post_title'])){
      $id = wp_insert_post(array('post_title'=>$program['post_title'], 'post_type'=>'extvs_schedule','post_status'=>'publish'));

      	add_post_meta($id,'extvs_schedule_date', strtotime($program['post_title']));
      	$category = get_term_by('slug', sanitize_title($program['timezone']), 'extvs_channel');
		wp_set_object_terms($id,$category->term_id,'extvs_channel');

        $keys = array_keys(array_column($programs, 'post_title'), $program['post_title']);

        $fnl = array();
        foreach ($keys as $key) {
          array_push($fnl, $programs[$key]);
        }
		add_post_meta($id,'extvs_schedule_addition_data',$fnl);

  $i++;
  }//endif

} // endforeach

echo $html;
}

 ?>
</div>
<?php } ?>