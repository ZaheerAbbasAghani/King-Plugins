<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Direct access restricted

function bmc_display_contents_by_shortcode($content){

$content .='<div class="bmcRocks"> <div class="card b-widget"><div class="card-body"><div class="columns key_partners" id="bc"><h3><i class="fa fa-users pull-left"></i>';
$content .= __( 'Key Partners', 'business-model-canvas' );
$content .= '</h3>';
global $wpdb;
$table_name = $wpdb->prefix . 'bmc_canvas_table';
$key_partners=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='key_partners' ");
foreach ( $key_partners as $key_partner ){   

if(empty($key_partner->bmc_color)){
$bkcolorKP = '#fffa90';
}else {
$bkcolorKP = $key_partner->bmc_color;
}

$content .=  '<div class="stickynote notes" style="background-color:'.esc_html($bkcolorKP).'">
<div class="stickyValues" data-name="'.esc_html( $key_partner->bmc_field ).'">'.stripslashes_from_strings_only(esc_html( $key_partner->bmc_value)).' </div></div>';
} 
$content .='</div>';



$content .= '<div class="bc_wrap" style="float: left; width: 20%;">
<div class="columns key_activities" id="bc" style="height: 300px; float: none; width: 100%;">
<h3><i class="fa fa-check-circle pull-left"></i>';
$content .= __( 'Key Activities', 'business-model-canvas' );
$content .=  '</h3>';

global $wpdb;
$table_name = $wpdb->prefix . 'bmc_canvas_table';
$key_activities=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='key_activities' ");

foreach ( $key_activities as $key_activity ){   
if(empty($key_activity->bmc_color)){
$bkcolorKA = '#fffa90';
}else {
$bkcolorKA = $key_activity->bmc_color;
}

$content .= '<div class="stickynote  notes" style="background-color:'.esc_html($bkcolorKA).'">
<div class="stickyValues" data-name="'.esc_html($key_activity->bmc_field).'">'.stripslashes_from_strings_only(esc_html($key_activity->bmc_value)).'</div>
</div>';
} 

//stripslashes_from_strings_only( $value );

$content .= '</div><!-- column -->';


$content .= '<div class="columns key_resources" id="bc" style="height: 300px; float: none;width: 100%;"><h3><i class="fa fa-ship pull-left"></i>';
$content .= __( 'Key Resources', 'business-model-canvas' );
$content .='</h3>';

global $wpdb;
$table_name = $wpdb->prefix . 'bmc_canvas_table';
$key_resources=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='key_resources' ");

foreach ( $key_resources as $key_resource ){   
if(empty($key_resource->bmc_color)){
$bkcolorKR = '#fffa90';
}else {
$bkcolorKR = $key_resource->bmc_color;
}
$content .= '<div class="stickynote  notes" style="background-color:'.esc_html($bkcolorKR).'">
<div class="stickyValues" data-name="'.esc_html($key_resource->bmc_field).'">'.esc_html($key_resource->bmc_value).'</div><!-- stickyValues --> </div>';
} 

$content .= '</div><!-- column --> </div><!--bc_wrap--> ';

$content .= '<div class="columns value_propositions" id="bc"><h3> <i class="fa fa-gift pull-left"></i>';
$content .= __( 'Value Propositions ', 'business-model-canvas' );
$content .= '</h3>';

global $wpdb;
$table_name = $wpdb->prefix . 'bmc_canvas_table';
$value_propositions=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='value_propositions' ");

foreach ( $value_propositions as $value_proposity ){ 
if(empty($value_proposity->bmc_color)){
$bkcolorVP = '#fffa90';
}else {
$bkcolorVP = $value_proposity->bmc_color;
}
$content .= '<div class="stickynote  notes" style="background-color:'.esc_html($bkcolorVP).'"><div class="stickyValues" data-name="'.esc_html($value_proposity->bmc_field).'">'.esc_html($value_proposity->bmc_value).'</div><!-- stickyValues --></div>';
} 

$content .= '</div><!-- column -->';

$content .= '<div class="bc_wrap" style="float: left; width: 20%;"><div class="columns customer_relationships" id="bc" style="height: 300px; float: none; width: 100%;"><h3><i class="fa fa-heart pull-left"></i>';
$content .= __( 'Customer Relationships', 'business-model-canvas' );
$content .=  '</h3>';

global $wpdb;
$table_name = $wpdb->prefix . 'bmc_canvas_table';
$customer_relationships=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='customer_relationships' ");

foreach ( $customer_relationships as $customer_relationship ){   
if(empty($customer_relationship->bmc_color)){
$bkcolorCR = '#fffa90';
}else {
$bkcolorCR = $customer_relationship->bmc_color;
}
$content .= '<div class="stickynote  notes" style="background-color:'.esc_html($bkcolorCR).'"><div class="stickyValues" data-name="'.esc_html($customer_relationship->bmc_field).'">'.esc_html($customer_relationship->bmc_value).' </div><!-- stickyValues --></div>';
} 
$content .='  </div><!-- column -->';


$content .= '<div class="columns channels" id="bc" style="height: 300px; float: none; width: 100%;"><h3><i class="fa fa-gift pull-left"></i>';
$content .= __( 'Channels', 'business-model-canvas' );
$content .= '</h3>';

global $wpdb;
$table_name = $wpdb->prefix . 'bmc_canvas_table';
$channels=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='channels' ");

foreach ( $channels as $channel ){   
if(empty($channel->bmc_color)){
$bkcolorCH = '#fffa90';
}else {
$bkcolorCH = $channel->bmc_color;
}
$content .= ' <div class="stickynote  notes" style="background-color:'.esc_html($bkcolorCH).'"><div class="stickyValues" data-name="'.esc_html($channel->bmc_field).'">'.esc_html($channel->bmc_value).'</div><!-- stickyValues --></div>';
} 
$content .= '</div><!-- column --></div><!-- bc_wrap -->';


$content .= '<div class="columns customer_segments" id="bc"><h3><i class="fa fa-user pull-left"></i>';
$content .= __( 'Customer Segments', 'business-model-canvas' );
$content .= '</h3>';

global $wpdb;
$table_name = $wpdb->prefix . 'bmc_canvas_table';
$customer_segments=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='customer_segments' ");
foreach ( $customer_segments as $customer_segment ){  
if(empty($customer_segment->bmc_color)){
$bkcolorCS = '#fffa90';
}else {
$bkcolorCS = $customer_segment->bmc_color;
}
$content .= '<div class="stickynote  notes" style="background-color:'. esc_html($bkcolorCS).'"><div class="stickyValues" data-name="'.esc_html($customer_segment->bmc_field).'">'.esc_html($customer_segment->bmc_value).' </div><!-- stickyValues --></div>';

} 

$content .= '</div><!-- column -->';



$content .= '<div class="columns cost_structure" id="bc" style="width: 50%; height: 300px;"><h3> <i class="fa fa-pie-chart"></i>';
$content .= __( 'Cost Structure', 'business-model-canvas' );
$content .= '</h3>';

global $wpdb;
$table_name = $wpdb->prefix . 'bmc_canvas_table';
$cost_structure=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='cost_structure' ");

foreach ( $cost_structure as $cost_struct ){ 
if(empty($cost_struct->bmc_color)){
$bkcolorCSR = '#fffa90';
}else {
$bkcolorCSR = $cost_struct->bmc_color;
}
$content .= ' <div class="stickynote  notes" style="background-color:'.esc_html($bkcolorCSR).'"><div class="stickyValues" data-name="'.esc_html($cost_struct->bmc_field).'">'.esc_html($cost_struct->bmc_value).' </div><!-- stickyValues --></div>';
} 
$content .= '</div><!-- columns -->';

$content .= '<div class="columns revenue_streams" id="bc" style="width: 50%;height: 300px;"><h3><i class="fa fa-thumbs-up pull-left"></i>';
$content .= __( 'Revenue Streams', 'business-model-canvas' );
$content .='</h3>';
global $wpdb;
$table_name = $wpdb->prefix . 'bmc_canvas_table';
$revenue_streams=$wpdb->get_results("SELECT * FROM $table_name where bmc_container='revenue_streams' ");

foreach ( $revenue_streams as $revenue_stream ){  
if(empty($revenue_stream->bmc_color)){
$bkcolorRS = '#fffa90';
}else {
$bkcolorRS = $revenue_stream->bmc_color;
} 
$content .= '<div class="stickynote  notes" style="background-color:'.esc_html($bkcolorRS).'"><div class="stickyValues" data-name="'.esc_html($revenue_stream->bmc_field).'">'.esc_html($revenue_stream->bmc_value).'</div><!-- stickyValues --></div>';

} 

$content .= '</div><!-- columns -->';


$content .= ' </div><!-- card-body --></div></div><!-- card --> ';


return $content;

}

add_shortcode( 'BMC', 'bmc_display_contents_by_shortcode' );