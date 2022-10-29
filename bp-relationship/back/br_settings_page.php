<?php
// create custom plugin settings menu
add_action('admin_menu', 'my_cool_plugin_create_menu');

function my_cool_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('BP Relationship', 'Relationship', 'manage_options', 'bp_relationship', 'br_plugins_settings_page' , 'dashicons-groups', 30 );

	//call register settings function
	add_action( 'admin_init', 'register_br_plugin_settings' );
}


function register_br_plugin_settings() {
	//register our settings
	register_setting( 'br-plugin-settings-group', 'fr_options' );
	/*register_setting( 'br-plugin-settings-group', 'some_other_option' );
	register_setting( 'br-plugin-settings-group', 'option_etc' );*/
}

function br_plugins_settings_page() {
?>
<div class="wrap" style="background: #fff;padding: 10px 20px;">
<h1>Relationship Settings</h1><hr>  

<form method="post" action="" id="zci_br_relations_form" style="background: #eee;padding: 15px;margin-bottom: 5%;">
<table class="table table-bordered" id="dynamic_field" style="width: 100%;">
    <tr>
        <td>
            <!--div class="top-row"-->
            
            <div class="field-wrap">
                
                <input type="text" name="zci_br_relations[]" required placeholder="Enter Relationship" style="width: 100%;"/>
            </div>
            
        </td>
        <td><button type="button" name="add" id="add" class="button button-default">Add More</button></td>
    </tr>
</table>
<input type="button" name="submit" id="br_submit"  class="button button-primary" value="Submit" />
</form>


<div class="zciRight">
    
<table id="zci_br_relation_table" class="display" style="width:100%;text-align: center;">
<thead>
    <tr>
        <th>ID</th>
        <th>Relationship</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    <?php 

global $wpdb;
$table_name = $wpdb->base_prefix.'br_relationship';
$i=1;
$relations = $wpdb->get_results( "SELECT * FROM $table_name", OBJECT ); ?>
 <?php foreach ($relations as $relation) { ?>
    <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $relation->br_relation; ?></td>
        <td><a href="#" class="zci_remove" id="<?php echo $relation->id; ?>"> Remove </a></td>
    </tr>
<?php $i++; } ?>
</tbody>
<tfoot>
   <tr>
        <th>ID</th>
        <th>Relationship</th>
        <th>Action</th>
    </tr>
</tfoot>
</table>

</div>


</div>
<?php } ?>