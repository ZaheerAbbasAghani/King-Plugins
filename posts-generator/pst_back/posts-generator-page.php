<?php
// create custom plugin settings menu
add_action('admin_menu', 'pst_plugin_create_menu');

function pst_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Post Generator', 'Posts Generator', 'manage_options', 'posts_generator_settings', 'pst_plugin_settings_page' , 'dashicons-edit-large');

	//call register settings function
	add_action( 'admin_init', 'register_my_cool_plugin_settings' );
}


function register_my_cool_plugin_settings() {
	//register our settings
	register_setting( 'pst-plugin-settings-group', 'new_option_name' );
	register_setting( 'pst-plugin-settings-group', 'some_other_option' );
	register_setting( 'pst-plugin-settings-group', 'option_etc' );
}

function pst_plugin_settings_page() {
?>
<div class="wrap posts_generator">
<h1>Posts Geneator</h1><hr>

<form action="" method="post" enctype="multipart/form-data">
	<input type="file" name="posts_generator[]" id="posts_generator" multiple>
	<input type="submit" value="Upload Files" name="submit">
</form>


<?php 

/*if(isset($_FILES['posts_generator'])){
//print_r($_FILES['posts_generator']);
	$fp = fopen($_FILES['posts_generator']['tmp_name'], 'rb');
    while ( ($line = fgets($fp)) !== false) {
      echo "$line<br>";
    }
}*/

if (isset ($_FILES["posts_generator"])) {
  $tot = count($_FILES["posts_generator"]["name"]);
  for ($i = 0; $i < $tot; $i++){
    echo $_name=$_FILES["posts_generator"]["name"][$i].'<br>';
    $_type=$_FILES["posts_generator"]["type"][$i];
    $_size=$_FILES["posts_generator"]["size"][$i];
    $_temp=$_FILES["posts_generator"]["tmp_name"][$i]; 
    $fp = fopen($_temp, "rb");
    $line = fgets($fp);
	echo "$line<br>";
    
  }
}


?>

<?php /*
<form method="post" action="options.php">
    <?php settings_fields( 'pst-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'pst-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">New Option Name</th>
        <td><input type="text" name="new_option_name" value="<?php echo esc_attr( get_option('new_option_name') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Some Other Option</th>
        <td><input type="text" name="some_other_option" value="<?php echo esc_attr( get_option('some_other_option') ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Options, Etc.</th>
        <td><input type="text" name="option_etc" value="<?php echo esc_attr( get_option('option_etc') ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
*/ ?>

</div>
<?php } ?>