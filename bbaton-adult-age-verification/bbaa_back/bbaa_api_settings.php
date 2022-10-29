<?php
// create custom plugin settings menu
add_action('admin_menu', 'bbaa_plugin_create_menu');

function bbaa_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('BBaton Settings', 'BBaton Settings', 'manage_options', 'bbaa_settings', 'bbaa_plugin_settings_page' , 'dashicons-yes-alt', 25 );

	//call register settings function
	add_action( 'admin_init', 'register_my_bbaa_plugin_settings' );
}


function register_my_bbaa_plugin_settings() {
	//register our settings
	register_setting( 'bbaa-plugin-settings-group', 'bbaa_client_id' );
	register_setting( 'bbaa-plugin-settings-group', 'bbaa_client_secret' );
	register_setting( 'bbaa-plugin-settings-group', 'bbaa_redirect_url' );
    register_setting( 'bbaa-plugin-settings-group', 'bbaa_lock_pages' );
        

}

function bbaa_plugin_settings_page() {
?>
<script type="text/javascript">
    
    jQuery(document).ready(function(){
        
        jQuery("#all_pages_parent option").each(function(){
            if(jQuery(this).attr('status') == 0){
                jQuery(this).show();
            }else{
                jQuery(this).hide();
            }
        });

        //console.log(status);
        
    }); 

</script>
<div class="wrap" style="background: #fff;padding: 10px 15px;box-shadow: 1px 1px 3px #ddd, -1px -1px 3px #ddd;">
<h1><?php echo __('BBaton Adult Age Verification'); ?> </h1><hr>

<?php settings_errors();?>

<form method="post" action="options.php">
    <?php settings_fields( 'bbaa-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'bbaa-plugin-settings-group' ); ?>

<?php 
    $bbaa_lock_pages =  get_option('bbaa_lock_pages'); 
    $bbaa_headings =  get_option('bbaa_headings'); 
  ?>

    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php echo esc_attr( __( 'Client ID' ) ); ?></th>
        <td><input type="text" name="bbaa_client_id" value="<?php echo esc_attr( get_option('bbaa_client_id') ); ?>" style="width:70%;"/></td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><?php echo esc_attr( __( 'Client Secret' ) ); ?></th>
        <td><input type="text" name="bbaa_client_secret" value="<?php echo esc_attr( get_option('bbaa_client_secret') ); ?>" style="width:70%;"/></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php echo esc_attr( __( 'Redirect URL' ) ); ?></th>
        <td><input type="text" name="bbaa_redirect_url" value="<?php echo esc_attr( get_option('bbaa_redirect_url') ); ?>" style="width:70%;"/></td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><?php echo esc_attr( __( 'Select page' ) ); ?></th>
            <td>
            <select name="bbaa_lock_pages[pages][]" multiple="multiple" style="width:100%;    max-width: 70%;" id="all_pages_parent"> 
        <option value="9999999999999999999" style="text-transform:capitalize;" status="0"

        <?php  $none = $bbaa_lock_pages['pages'][0];

        if($none == "9999999999999999999"){
            echo "selected='selected' ";
        } ?> >None</option>


                <?php 


                    $pages = get_pages(); 
           
                    $i=0;

                    foreach ( $pages as $page ) {
                        if(in_array($page->ID, $bbaa_lock_pages['pages']) ){
                            $option = '<option value="' .  $page->ID  . '" style="text-transform:capitalize;" selected="selected" status="'.$page->post_parent.'">';
                            $option .= $page->post_title;
                            $option .= '</option>';
                        }else{
                            $option = '<option value="' .  $page->ID  . '" style="text-transform:capitalize;" status="'.$page->post_parent.'">';
                            $option .= $page->post_title;
                            $option .= '</option>';

                        }
                    echo $option;
                    $i++;
                }
                ?>
            </select>
            </td>
        </tr>

        

    </table>

    <?php submit_button(); ?>

</form>
</div>
<?php } ?>