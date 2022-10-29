<?php
// create custom plugin settings menu
add_action('admin_menu', 'rtf_plugin_create_menu');

function rtf_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Refer to friend Settings', 'Refer to friend ', 'manage_options', 'rtf_refer_to_friend', 'rtf_plugin_settings_page' , 'dashicons-admin-appearance' , 25 );

    add_submenu_page('rtf_refer_to_friend', 'Refer to friend Settings', 'Refer to friend ', 'manage_options', 'rtf_refer_to_friend');


    add_submenu_page( 'rtf_refer_to_friend', 'Contact Details', 'Contact Details', 'manage_options', 'rtf-contact-details-page', 'rtf_contact_details_page' ); 


	//call register settings function
	add_action( 'admin_init', 'register_rtf_plugin_settings' );
}


function register_rtf_plugin_settings() {
	//register our settings
	register_setting( 'rtf-plugin-settings-group', 'rtf_form_title' );
    register_setting( 'rtf-plugin-settings-group', 'rtf_popup_width');
	register_setting( 'rtf-plugin-settings-group', 'rtf_upload_logo_url' );
	register_setting( 'rtf-plugin-settings-group', 'rtf_background_color' );
    register_setting( 'rtf-plugin-settings-group', 'rtf_text_color' );
    register_setting( 'rtf-plugin-settings-group', 'rtf_placeholder_color' );
    register_setting( 'rtf-plugin-settings-group', 'rtf_opacity' );
    register_setting( 'rtf-plugin-settings-group', 'rtf_field_text_color' );
    register_setting( 'rtf-plugin-settings-group', 'rtf_form_field_height' );
    register_setting( 'rtf-plugin-settings-group', 'rtf_form_field_width' );
    register_setting( 'rtf-plugin-settings-group', 'rtf_form_font_size' );

    register_setting( 'rtf-plugin-settings-group', 'rtf_button_text');
    register_setting( 'rtf-plugin-settings-group', 'rtf_button_background');
    register_setting( 'rtf-plugin-settings-group', 'rtf_border_radius');
}

function rtf_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff;padding: 10px 20px;clear: both;height: auto;overflow: hidden;">
<h1>Refer to friend - Settings </h1> <hr>

<?php // Field Maker Part ?>
<div class="rtf_left_side" style="float:left;width: 56%;">

<a href="#" class="button button-primary" id="add_field"> Create Field </a>

<?php 
global $wpdb;
$table_name = $wpdb->base_prefix.'rtf_fields_maker';

$query = "SELECT * FROM $table_name ORDER BY forder";
$query_results = $wpdb->get_results($query);

if(!empty($query_results)){
    echo "<div class='anamnese_settings'>";
    foreach ($query_results as $result) {
            //$string = str_replace(' ', '', strtolower($result->label));
            echo "<ul id='".$result->id."'>
                <li><span>Field Label: </span>".$result->label." </li>
                <li> <span>Field Type: </span>".$result->fieldtype." </li>
                <li><a href='#' class='edit_field button button-default' data-id='".$result->id."'> Edit </a></li>
                <li><a href='#' class='delete_field button button-default' data-id='".$result->id."' data-column='".$result->fname."'> Delete </a></li>
            </ul>";

    }
    echo "</div>";
}else{
    echo "<h3> No Field Found! </h3>";
}

?>


</div>

<div class="rtf_right_side" style="float: right;background: #eee;padding: 10px 15px;box-shadow: 2px 2px 2px #ddd,-2px -2px 2px #ddd;width: 40%;">

    <?php settings_errors(); ?>
    <form method="post" action="options.php">
        <?php settings_fields( 'rtf-plugin-settings-group' ); ?>
        <?php do_settings_sections( 'rtf-plugin-settings-group' ); ?>
        <table class="form-table">

            <tr valign="top">
            <td scope="row" style="padding:0px !important;"><h3>Form Settings</h3><hr></td>
            </tr>
            
            <tr valign="top">
            <th scope="row">Form Title</th>
            <td><input type="text" name="rtf_form_title" value="<?php echo esc_attr( get_option('rtf_form_title') ); ?>" /></td>
            </tr>

            <tr valign="top">
            <th scope="row">Popup Width</th>
            <td><input type="text" name="rtf_popup_width" value="<?php echo esc_attr( get_option('rtf_popup_width') ); ?>" /></td>
            </tr>

            <tr valign="top">
            <th scope="row">Popup Opacity</th>
            <td><input type="text" name="rtf_opacity" value="<?php echo esc_attr( get_option('rtf_opacity') ); ?>" placeholder="0.1, 0.2, 0.3 till 1"/></td>
            </tr>

            <tr valign="top">
            <th scope="row">Form Field Height</th>
            <td><input type="text" name="rtf_form_field_height" value="<?php echo esc_attr( get_option('rtf_form_field_height') ); ?>" placeholder="15px"/></td>
            </tr>

            <tr valign="top">
            <th scope="row">Form Field Width</th>
            <td><input type="text" name="rtf_form_field_width" value="<?php echo esc_attr( get_option('rtf_form_field_width') ); ?>" placeholder="15px or 15%"/></td>
            </tr>

            <tr valign="top">
            <th scope="row">Form Field Font Size</th>
            <td><input type="text" name="rtf_form_font_size" value="<?php echo esc_attr( get_option('rtf_form_font_size') ); ?>" placeholder="17px"/></td>
            </tr>
            

            <tr valign="top">
            <th scope="row">Form Background Color</th>
            <?php if(empty(esc_attr( get_option('rtf_background_color') ))){ ?>
                <td><input class="my-color-field" type="text" value="#bada55" data-default-color="#effeff" data-alpha-enabled="true"/>
            <?php } else { ?>

                <td><input class="my-color-field" type="text" value="<?php echo esc_attr( get_option('rtf_background_color') ); ?>" data-default-color="<?php echo esc_attr( get_option('rtf_background_color') ); ?>" data-alpha-enabled="true"/>
            <?php } ?>
            <input type="hidden" id="rtf_background_color" name="rtf_background_color" value="<?php echo esc_attr( get_option('rtf_background_color') ); ?>"  /></td>
            </tr>

            <tr valign="top">
            <th scope="row">Form Text Color</th>
            <?php if(empty(esc_attr( get_option('rtf_text_color') ))){ ?>
                <td><input class="my-color-field-2" type="text" value="#bada55" data-default-color="#effeff" data-alpha-enabled="true"/> </td>
            <?php } else { ?>

                <td><input class="my-color-field-2" type="text" value="<?php echo esc_attr( get_option('rtf_text_color') ); ?>" data-default-color="<?php echo esc_attr( get_option('rtf_text_color') ); ?>" data-alpha-enabled="true"/>
            <?php } ?>
            <input type="hidden" id="rtf_text_color" name="rtf_text_color" value="<?php echo esc_attr( get_option('rtf_text_color') ); ?>"  /></td>
            </tr>

            <tr valign="top">
            <th scope="row">Form Field Text Color</th>
            <?php if(empty(esc_attr( get_option('rtf_field_text_color') ))){ ?>
                <td><input class="my-color-field-3" type="text" value="#bada55" data-default-color="#effeff" data-alpha-enabled="true"/> </td>
            <?php } else { ?>

                <td><input class="my-color-field-3" type="text" value="<?php echo esc_attr( get_option('rtf_field_text_color') ); ?>" data-default-color="<?php echo esc_attr( get_option('rtf_field_text_color') ); ?>" data-alpha-enabled="true"/>
            <?php } ?>
            <input type="hidden" id="rtf_field_text_color" name="rtf_field_text_color" value="<?php echo esc_attr( get_option('rtf_field_text_color') ); ?>"  /></td>
            </tr>

            <tr valign="top">
            <td scope="row" style="padding:0px !important;"><h3>Button Settings</h3><hr></td>
            </tr>

            <tr valign="top">
            <th scope="row"> Button Text </th>
            <td><input type="text" name="rtf_button_text" value="<?php echo esc_attr( get_option('rtf_button_text') ); ?>" placeholder="Enter submit button text"/></td>
            </tr>


            <tr valign="top">
            <th scope="row">Button Background</th>
            <?php if(empty(esc_attr( get_option('rtf_button_background') ))){ ?>
                <td><input class="my-color-field-4" type="text" value="#bada55" data-default-color="#effeff" data-alpha-enabled="true"/> </td>
            <?php } else { ?>

                <td><input class="my-color-field-4" type="text" value="<?php echo esc_attr( get_option('rtf_button_background') ); ?>" data-default-color="<?php echo esc_attr( get_option('rtf_button_background') ); ?>" data-alpha-enabled="true"/>
            <?php } ?>
            <input type="hidden" id="rtf_button_background" name="rtf_button_background" value="<?php echo esc_attr( get_option('rtf_button_background') ); ?>"  /></td>
            </tr>


            <tr valign="top">
            <th scope="row"> Button Border Radius </th>
            <td><input type="text" name="rtf_border_radius" value="<?php echo esc_attr( get_option('rtf_border_radius') ); ?>" placeholder="Enter button radius"/></td>
            </tr>

           

        </table>
        
        <?php submit_button(); ?>
    </form>

</div><!-- rightSide -->



</div>
<?php } ?>


<?php 

// Submenu Page

function rtf_contact_details_page(){
    ?>

    <div class="wrap" style="background: #fff;padding: 10px 20px;clear: both;height: auto;overflow: hidden;">

    <h1>Contact Details </h1> <hr>

    <div class="page2wrap">
    <?php 

    global $wpdb;
    $table_name = $wpdb->base_prefix.'rtf_store_info';
    $table_name1 = $wpdb->base_prefix.'rtf_fields_maker';

    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'page',
    );
    $the_query = new WP_Query( $args );

   
    if($the_query->have_posts()): while($the_query->have_posts()): $the_query->the_post();
        $id = urlencode(get_the_title());

      //  echo $id;
        
        $query = "SELECT * FROM $table_name WHERE page_id_field='$id' ";
        $results = $wpdb->get_results($query, ARRAY_A);


        $query1 = "SELECT * FROM $table_name1";
        $results1 = $wpdb->get_results($query1, ARRAY_A);



        if(!empty($results)){

            echo '<div class="rtf_accordion"> <h3>'.get_the_title().'</h3>';
            echo "<div><table  class='display' style='width:100%'>
                        <thead><tr>";


            foreach (array_reverse($results1) as  $v) {
                 echo "<th>".$v['label']."</th>";
            }
            echo "<th>Page ID</th>";
            echo "<th>Created at</th>";
                           
            echo "</tr></thead><tbody>";
            $i = 1;
            foreach($results as $key => $value) {
                $final = array_shift($value);
                echo "<tr>";
                foreach ($value as $val) {
                    if(!empty($val)){
                        echo "<td>".urldecode($val)."</td>";
                    }else{
                        echo "<td> - </td>";
                    }
                }
                echo "</tr>";
                $i++;
            }
            echo "</tbody></table> </div>";
            echo "</div>";
          
        }


    endwhile;
    endif;
?>

    </div><!-- page2wrap -->

    <div class="preload"><img src="http://i.imgur.com/KUJoe.gif"></div>
    </div>

    <?php 
}


?>