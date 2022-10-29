<?php 


function ts_custom_add_premium_support_endpoint() {
    add_rewrite_endpoint( 'your-messages', EP_ROOT | EP_PAGES );
}
  
add_action( 'init', 'ts_custom_add_premium_support_endpoint' );
  
  
/**
 * 2. Add new query var
 */
  
function ts_custom_premium_support_query_vars( $vars ) {
    $vars[] = 'your-messages';
    return $vars;
}
  
add_filter( 'woocommerce_get_query_vars', 'ts_custom_premium_support_query_vars', 0 );
  
  
/**
 * 3. Insert the new endpoint into the My Account menu
 */
  
function ts_custom_add_premium_support_link_my_account( $items ) {
    $items['your-messages'] = 'Messages';
    return $items;
}
  
add_filter( 'woocommerce_account_menu_items', 'ts_custom_add_premium_support_link_my_account' );
  
  
/**
 * 4. Add content to the new endpoint
 */
  
function cm_custom_send_message_content() {
	//echo '<h3>Your Messages</h3><hr>';


echo "<form method='post' action='' id='send_message'>

<label> Receiver Number <input type='number' name='receiver_number' id='receiver_number'  placeholder='+13867424629' required> </label>

<label> Message <textarea cols='5' rows='5' placeholder='Enter Your Message' id='message' required></textarea> </label>

<input type='submit'>

</form>";

echo "<h3 style='margin-top: 40px;border-bottom: 1px solid #ddd;padding-bottom: 10px;'>Message History</h3>";

global $wpdb; 
$table_name = $wpdb->base_prefix.'cm_messages_per_user';

$twilio_number = "+13867424629";

$query = "SELECT * FROM $table_name WHERE sender_number='$twilio_number'";
$query_results = $wpdb->get_results($query);
$i=1;
if(!empty($query_results)){
	echo "<table>";
    echo "<tr><th>#</th><th>Receiver Number</th><th>Sender Number</th><th>Message</th><th>Created Date/Time</th></tr>";
    $i=1;
    foreach($query_results as $q){
     echo "<tr><td>".$i."</td><td>".$q->receiver_number."</td><td>".$q->sender_number."</td><td>".$q->message."</td><td>".$q->created_date."</td></tr>";
     $i++;
    }
    echo "</table>";
}






}

/**
 * @important-note	"add_action" must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format
 */
add_action( 'woocommerce_account_your-messages_endpoint', 'cm_custom_send_message_content' );