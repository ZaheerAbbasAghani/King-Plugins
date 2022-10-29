<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Direct access restricted

// create custom plugin settings menu
add_action('admin_menu', 'bmc_register_custom_page');

function bmc_register_custom_page() {

//create new top-level menu
add_menu_page('Business Canvas Model', 'Business Canvas Model', 'manage_options', 'bmc_business_canvas_model', 'bmc_canvases_in_tabs' , 'dashicons-megaphone', 25 );

//call register settings function
add_action( 'admin_init', 'bmc_register_plugin_settings' );
}


//add_action( 'admin_init', 'Dropdown_settings_init' );

function bmc_register_plugin_settings(  ) { 
register_setting( 'business_canvas_model', 'bmc_sticky_note' );
}


function bmc_canvases_in_tabs() {  ?>
<div class="bmc_wrapper">
<h1>Business Canvas Models </h1><hr>

<ul class="nav nav-tabs">
<li class="nav-item">
<a class="nav-link active" data-toggle="tab" href="#business_canvas">Business Canvas</a>
</li>
<!--   <li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#swot_analysis">Swot Analysis</a>
</li>
<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#value_proposition_canvas">Value Proposition Canvas</a>
</li> -->
</ul>

<!-- Tab panes -->
<div class="tab-content">

<div class="tab-pane active" id="business_canvas">
    <div class="container-fluid">

    <form method="post"  action="options.php" id="canvas_form">

<?php settings_fields( 'business_canvas_model' ); ?>
<?php do_settings_sections( 'business_canvas_model' ); ?>

<div class="card b-widget">
<div class="card-body">
            
<div class="columns key_partners" id="bc">  
  <h3>
    <i class="fa fa-users pull-left"></i> 
    <?php _e( 'Key Partners', 'business-model-canvas' ); ?> 
    <i class="fa fa-plus pull-right addsticky"></i>
  </h3>

    <?php 
    global $wpdb;
    $table_name = $wpdb->prefix . 'bmc_canvas_table';
    $key_partners=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='key_partners' ");

    foreach ( $key_partners as $key_partner ){   ?>
    <div class="stickynote notes" style="background-color:<?php echo esc_html($key_partner->bmc_color); ?>"  >
    <span class="delete" data-id="<?php echo esc_html($key_partner->id); ?>">x</span>
    <div class="stickyValues" data-name="<?php echo esc_html($key_partner->bmc_field); ?>"><?php echo esc_html($key_partner->bmc_value); ?> </div><!-- stickyValues -->
    </div>
    <?php } ?>


</div><!-- column -->
            
<div class="bc_wrap" style="float: left; width: 20%;">
<div class="columns key_activities" id="bc" style="height: 300px; float: none; width: 100%;">
  <h3>
    <i class="fa fa-check-circle pull-left"></i> 
    <?php _e( 'Key Activities', 'business-model-canvas' ); ?> 
    <i class="fa fa-plus pull-right addsticky"></i>
  </h3>   
  <?php 
  global $wpdb;
  $table_name = $wpdb->prefix . 'bmc_canvas_table';
  $key_activities=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='key_activities' ");

  foreach ( $key_activities as $key_activity ){   ?>
  <div class="stickynote  notes" style="background-color:<?php echo esc_html($key_activity->bmc_color); ?>">
  <span class="delete" data-id="<?php echo esc_html($key_activity->id); ?>">x</span>
  <div class="stickyValues" data-name="<?php echo esc_html($key_activity->bmc_field); ?>"><?php echo esc_html($key_activity->bmc_value); ?> </div><!-- stickyValues -->
  </div>
  <?php } ?>
</div><!-- column -->
                
<div class="columns key_resources" id="bc" style="height: 300px; float: none;width: 100%;">
<h3>
    <i class="fa fa-ship pull-left"></i>
    <?php _e( 'Key Resources ', 'business-model-canvas' ); ?> 
    <i class="fa fa-plus pull-right addsticky"></i>
</h3>   
  <?php 
  global $wpdb;
  $table_name = $wpdb->prefix . 'bmc_canvas_table';
  $key_resources=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='key_resources' ");

  foreach ( $key_resources as $key_resource ){   ?>
  <div class="stickynote  notes" style="background-color:<?php echo esc_html($key_resource->bmc_color);  ?>">
  <span class="delete" data-id="<?php echo esc_html($key_resource->id); ?>">x</span>
  <div class="stickyValues" data-name="<?php echo esc_html($key_resource->bmc_field); ?>"><?php echo esc_html($key_resource->bmc_value); ?> </div><!-- stickyValues -->
  </div>
  <?php } ?>
</div><!-- column -->
</div><!--bc_wrap-->

<div class="columns value_propositions" id="bc">
  <h3> 
    <i class="fa fa-gift pull-left"></i> 
    <?php _e( 'Value Propositions', 'business-model-canvas' ); ?> 
    <i class="fa fa-plus pull-right addsticky"></i>
  </h3>

  <?php 
  global $wpdb;
  $table_name = $wpdb->prefix . 'bmc_canvas_table';
  $value_propositions=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='value_propositions' ");

  foreach ( $value_propositions as $value_proposity ){   ?>
  <div class="stickynote  notes" style="background-color:<?php echo esc_html($value_proposity->bmc_color); ?>">
  <span class="delete" data-id="<?php echo esc_html($value_proposity->id); ?>">x</span>
  <div class="stickyValues" data-name="<?php echo esc_html($value_proposity->bmc_field); ?>"><?php echo esc_html($value_proposity->bmc_value); ?> </div><!-- stickyValues -->
  </div>
  <?php } ?>
</div><!-- column -->

<div class="bc_wrap" style="float: left; width: 20%;">
<div class="columns customer_relationships" id="bc" style="height: 300px; float: none; width: 100%;">
  <h3>
    <i class="fa fa-heart pull-left"></i> 
    <?php _e( 'Customer Relationships', 'business-model-canvas' ); ?> 
    <i class="fa fa-plus pull-right addsticky"></i>
  </h3>
  <?php 
  global $wpdb;
  $table_name = $wpdb->prefix . 'bmc_canvas_table';
  $customer_relationships=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='customer_relationships' ");

  foreach ( $customer_relationships as $customer_relationship ){   ?>
  <div class="stickynote  notes" style="background-color:<?php echo esc_html($customer_relationship->bmc_color); ?>">
  <span class="delete" data-id="<?php echo esc_html($customer_relationship->id); ?>">x</span>
  <div class="stickyValues" data-name="<?php echo esc_html($customer_relationship->bmc_field); ?>"><?php echo esc_html($customer_relationship->bmc_value); ?> </div><!-- stickyValues -->
  </div>
  <?php } ?>
</div><!-- column -->


<div class="columns channels" id="bc" style="height: 300px; float: none; width: 100%;">
  <h3>
    <i class="fa fa-gift pull-left"></i>
    <?php _e( 'Channels', 'business-model-canvas' ); ?>  
    <i class="fa fa-plus pull-right addsticky"></i> 
  </h3>

  <?php 
  global $wpdb;
  $table_name = $wpdb->prefix . 'bmc_canvas_table';
  $channels=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='channels' ");

  foreach ( $channels as $channel ){   ?>
  <div class="stickynote  notes" style="background-color:<?php echo esc_html($channel->bmc_color); ?>">
  <span class="delete" data-id="<?php echo esc_html($channel->id); ?>">x</span>
  <div class="stickyValues" data-name="<?php echo esc_html($channel->bmc_field); ?>"><?php echo esc_html($channel->bmc_value); ?> </div><!-- stickyValues -->
  </div>
  <?php } ?>
</div><!-- column -->
</div><!-- bc_wrap -->


<div class="columns customer_segments" id="bc">
  <h3>
    <i class="fa fa-user pull-left"></i>
    <?php _e( 'Customer Segments', 'business-model-canvas' ); ?>
    <i class="fa fa-plus pull-right addsticky"></i>
  </h3>
  <?php 
  global $wpdb;
  $table_name = $wpdb->prefix . 'bmc_canvas_table';
  $customer_segments=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='customer_segments' ");

  foreach ( $customer_segments as $customer_segment ){   ?>
  <div class="stickynote  notes" style="background-color:<?php echo esc_html($customer_segment->bmc_color); ?>">
  <span class="delete" data-id="<?php echo esc_html($customer_segment->id); ?>">x</span>
  <div class="stickyValues" data-name="<?php echo esc_html($customer_segment->bmc_field); ?>"><?php echo esc_html($customer_segment->bmc_value); ?> </div><!-- stickyValues -->
  </div>
  <?php } ?>
</div><!-- column -->
         
         

<div class="columns cost_structure" id="bc" style="width: 50%; height: 300px;">
<h3> 
  <i class="fa fa-pie-chart"></i> 
  <?php _e( 'Cost Structure', 'business-model-canvas' ); ?>
  <i class="fa fa-plus pull-right addsticky"></i> 
</h3> 

  <?php 
  global $wpdb;
  $table_name = $wpdb->prefix . 'bmc_canvas_table';
  $cost_structure=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='cost_structure' ");

  foreach ( $cost_structure as $cost_struct ){   ?>
  <div class="stickynote  notes" style="background-color:<?php echo esc_html($cost_struct->bmc_color); ?>">
  <span class="delete" data-id="<?php echo esc_html($cost_struct->id); ?>">x</span>
  <div class="stickyValues" data-name="<?php echo esc_html($cost_struct->bmc_field); ?>"><?php echo esc_html($cost_struct->bmc_value); ?> </div><!-- stickyValues -->
  </div>
  <?php } ?>
</div><!-- columns -->

<div class="columns revenue_streams" id="bc" style="width: 50%;height: 300px;">
  <h3> 
    <i class="fa fa-thumbs-up pull-left"></i>
    <?php _e( 'Revenue Streams', 'business-model-canvas' ); ?>
    <i class="fa fa-plus pull-right addsticky"></i>
  </h3>

  <?php 
  global $wpdb;
  $table_name = $wpdb->prefix . 'bmc_canvas_table';
  $revenue_streams=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='revenue_streams' ");

  foreach ( $revenue_streams as $revenue_stream ){   ?>
  <div class="stickynote  notes" style="background-color:<?php echo esc_html($revenue_stream->bmc_color); ?>">
  <span class="delete" data-id="<?php echo esc_html($revenue_stream->id); ?>">x</span>
  <div class="stickyValues" data-name="<?php echo esc_html($revenue_stream->bmc_field); ?>"><?php echo esc_html($revenue_stream->bmc_value); ?> </div><!-- stickyValues -->
  </div>
  <?php } ?>

</div><!-- columns -->
             
     
         </div><!-- card-body -->
    </div><!-- card --> <br>
<?php
$other_attributes = array(
    'id' => 'create_sticky_notes'
);
submit_button("Submit", "primary", "button", false, $other_attributes); 
?>
</form><!-- end form -->
</div><!-- container -->
</div><!-- business canvas -->

<!-- <div class="tab-pane container fade" id="swot_analysis">This is menu 1</div>
<div class="tab-pane container fade" id="value_proposition_canvas">This is menu 2</div> -->
</div>

</div><!-- bmc_wrapper --> 

<?php } ?>