<?php
// create custom plugin settings menu
add_action('admin_menu', 'tti_plugin_create_menu');

function tti_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Kurtaxen', 'Kurtaxe', 'manage_options', 'tti_tourist_tax_invoice_settings', 'tti_plugin_settings_page' , 'dashicons-palmtree', 25);

  /*add_submenu_page( 'my-top-level-slug', 'My Custom Page', 'My Custom Page',
    'manage_options', 'my-top-level-slug');*/
  add_submenu_page( 'tti_tourist_tax_invoice_settings', 'Übersicht', 'Übersicht', 'manage_options', 'tti_reservations', 'tti_reservations_information');

	//call register settings function
	add_action( 'admin_init', 'register_tti_plugin_settings' );
}


function register_tti_plugin_settings() {
	//register our settings
	register_setting( 'tti-plugin-settings-group', 'uploaded_logo' );
  
  register_setting( 'tti-plugin-settings-group', '0to9yearsMainSeason' );
  register_setting( 'tti-plugin-settings-group', '10to15yearsMainSeason' );
  register_setting( 'tti-plugin-settings-group', '16to99yearsMainSeason' );
  
  register_setting( 'tti-plugin-settings-group', '0to9yearsLowSeason' );
  register_setting( 'tti-plugin-settings-group', '10to15yearsLowSeason' );
  register_setting( 'tti-plugin-settings-group', '16to99yearsLowSeason' );
  
}

function tti_plugin_settings_page() {
?>
<div class="wrap" style="background: #fff; padding: 10px 20px; height: auto;overflow: hidden;">
<h1>Kurtaxe</h1> <hr>

<form id="myForm" action="#" name="myForm" method="POST">
  <h3>Neue Kurtaxen-Rechnung erstellen:</h3>
  <!-- One "tab" for each step in the form: -->
  <div class="tab">
    <p><input type="text" placeholder="Nachname" name="fullname" id="fullname" required></p>
    <p><input type="text" placeholder="Vorname" name="surname" id="surname" required></p>
    <p><input type="text" placeholder="Strasse und Nummer" name="address" id="address" required></p>
    <p><input type="text" placeholder="Postleitzahl" name="zipcode" id="zipcode" required></p>
    <p><input type="text" placeholder="Stadt" name="city" id="city" required></p>
    <p><input type="number" placeholder="Anzahl der Reisenden" name="number_of_person" id="number_of_person" required></p>
  </div>
  <div class="tab"> 
    <div class="wrap_persons"></div>
  </div>
  <div class="tab">

    <div class="wrap_live_invoice"></div>

  </div>
  <div style="overflow:auto;">
    <div style="float:right; margin-top: 5px;">
        <button type="button" class="next button button-primary">Weiter</button>
        <button type="submit" class="submit button button-primary">Erstellen</button>
    </div>
  </div>
  <!-- Circles which indicates the steps of the form: -->
  <div style="text-align:center;margin-top:40px;">
    <span class="step">1</span>
    <span class="step">2</span>
    <span class="step">3</span>
  </div>
</form>

<div class="tti_settings">

  <form method="post" action="options.php">
      <?php settings_fields( 'tti-plugin-settings-group' ); ?>
      <?php do_settings_sections( 'tti-plugin-settings-group' ); ?>
      <table class="form-table">
          <tr valign="top">
          <th scope="row">Einstellungen</th>
          <td>
              <button class="button tti-upload-logo">Hochladen</button>
              <input type="hidden" name="uploaded_logo" id="uploaded_logo" value="<?php echo esc_attr( get_option('uploaded_logo') ); ?>" />
              <div id="logo" style="background-image: url(<?php echo esc_attr( get_option('uploaded_logo') ); ?>);"></div>
          </td></tr>
           
          <tr>
            <th><p style="margin: 0px; color: red;"> HAUPTSAISON: </p></th>
          </tr>

          <tr valign="top"> 
          <th scope="row"> 0-9 Jahre: </th>
          <td><input type="text" name="0to9yearsMainSeason" value="<?php echo esc_attr( get_option('0to9yearsMainSeason') ); ?>" placeholder="Preis eingeben"/></td>
          </tr>

          <tr valign="top">
          <th scope="row">10-15 Jahre:</th>
          <td><input type="text" name="10to15yearsMainSeason" value="<?php echo esc_attr( get_option('10to15yearsMainSeason') ); ?>" placeholder="Preis eingeben"/></td>
          </tr>
          
          <tr valign="top">
          <th scope="row">16-99 Jahre:</th>
          <td><input type="text" name="16to99yearsMainSeason" value="<?php echo esc_attr( get_option('16to99yearsMainSeason') ); ?>" placeholder="Preis eingeben"/></td>
          </tr>

          <tr>
            <th><p style="margin: 0px; color: red;"> NEBENSAISON: </p></th>
          </tr>

          <tr valign="top">
          <th scope="row"> 0-9 Jahre:</th>
          <td><input type="text" name="0to9yearsLowSeason" value="<?php echo esc_attr( get_option('0to9yearsMainSeason') ); ?>" placeholder="Preis eingeben"/></td>
          </tr>

          <th scope="row">10-15 Jahre:</th>
          <td><input type="text" name="10to15yearsLowSeason" value="<?php echo esc_attr( get_option('10to15yearsLowSeason') ); ?>" placeholder="Preis eingeben"/></td>
          </tr>
          
          <tr valign="top">
          <th scope="row">16-99 Jahre:</th>
          <td><input type="text" name="16to99yearsLowSeason" value="<?php echo esc_attr( get_option('16to99yearsLowSeason') ); ?>" placeholder="Preis eingeben"/></td>
          </tr>



      </table>
      
      <?php submit_button("Erstellen"); ?>

  </form>

</div>


</div>
<?php }


// Submenu Page

function tti_reservations_information(){
  ?>

  <div class="wrap" style="background: #fff; padding: 10px 20px; height: auto;overflow: hidden;">

    <h1>Übersicht Kurtaxen-Rechnungen</h1> <hr>

    <?php 

    global $wpdb; 
    $table_name = $wpdb->base_prefix.'tti_tourist_bookings';

    $query = "SELECT * FROM $table_name ORDER BY id DESC";
    $query_results = $wpdb->get_results($query);


    ?>


    <table id="example" class="display" style="width:100%; text-align: center;">
        <thead>
            <tr>
                <th>Name</th>
                <th>Adresse</th>
                <th>PLZ</th>
                <th>Stadt</th>
                <th>Erstellt am</th>
                <th>Aktion</th>
            </tr>
        </thead>
        <tbody>

          <?php foreach ($query_results  as $results) { ?>
            <tr>
                <td><?php echo $results->fullname.' '.$results->surname; ?></td>
                <td><?php echo $results->address; ?></td>
                <td><?php echo $results->zipcode; ?></td>
                <td><?php echo $results->city; ?></td>
                <td><?php echo $results->created_date; ?></td> 
                <td><a href="#" data-id="<?php echo $results->id; ?>" class="delete_row"> L&ouml;schen </a> // <a href="#" data-id="<?php echo $results->id; ?>" class='invoice'> Anzeigen </a></td>
            </tr>
          <?php } ?>
        </tbody>
    </table>



  </div>

  <?php 
}

 ?>