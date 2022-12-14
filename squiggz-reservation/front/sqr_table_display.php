<?php 

function sqr_games_shortcode($atts){
   //print_r($atts);
   extract(shortcode_atts(array(
      'table' => $atts['table_id'],
   ), $atts));

   $return_string = "";
   $return_string .= "<div class='sqr_wrapper_top'>"; 

   $disabled = is_admin() == 1 ? "" : "disabled";
   $display  = is_admin() == 1 ? "hideit" : "showit";

   $return_string .= '<div class="adminReservationBar '. $display.'" name="adminReservationBar">
   <form method="post" action="" name="reserveNow" id="rereserveNowser" autocomplete="off">

   <div class="" style="display: flex;margin-bottom: 20px;">

      <input type="text" name="reservation_start_date_time" id="reservation_start_date_time" style="margin:0px 10px 0px 0px;display: block;width: 100%;padding: 5px 12px;font-size: 17px;" placeholder="Choose any date for reservation" readonly required value="'.date( 'Y-m-d', current_time( 'timestamp', 0 ) ).'">

      <input type="text" name="reservation_start_time" id="reservation_start_time" style="margin: 0px 10px;display: block;width: 100%;padding: 5px 12px;font-size: 17px;border:1px solid #ddd;" placeholder="Check booked seats by time"  required readonly>
   </div>';

        
   $return_string .= "<table class='sqr_wrapper has-background' border='1' data-table='".$table."' current-time='".date( 'H:i', current_time( 'timestamp', 0 ) )."'>";
  // $total_seats = get_post_meta($table, 'total_seats', true);

   $sqr_game_name    = get_post_meta( $table, 'sqr_game_name', true);
   $seats_to_fill    = get_post_meta( $table, 'seats_to_fill', true);
   $sqr_game_color   = get_post_meta($table, 'sqr_game_color', true);

   $after_login_redirect = get_option("after_login_redirect") == "" ? get_site_url()."/wp-admin" : get_option("after_login_redirect");

   global $wpdb;
   $table_name = $wpdb->prefix . 'sqr_squizz_reservation';
   $results = $wpdb->get_results ("SELECT * FROM $table_name where floor_id='$table'");

   $spots_reserved = array();
   foreach ($results as $result) {
      array_push($spots_reserved, $result->spot_selected);
   }


 
   $max_per_row = 35;
   $item_count = 0;

   $return_string .= "<tr data-id=".$table.">";
   for ($i=1; $i <= 350; $i++) { 

      if ($item_count == $max_per_row)
      {
           $return_string.= "</tr><tr data-id=".$table.">";
           $item_count = 0;
      }


      if(is_user_logged_in()):

         $return_string.="<td><span class='spot ".$i."'> ".$i."</span></td>";

      else:
         $return_string.="<td title='".esc_attr( get_option('reservation_login_message') )."' class='loginPlease'><span class='spot ".$i." makeLogin'>".$i."</span></td>";
      endif;


      $item_count++;
   }
   
   $return_string .= "</tr>";
   $return_string .= "</table>";

   $return_string .='</form></div>';


   return $return_string;
}

add_shortcode("table", "sqr_games_shortcode");