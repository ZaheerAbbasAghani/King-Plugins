<?php
$bbaa_lock_pages = array( 'bbaa_client_id', 'bbaa_client_secret', 'bbaa_redirect_url', 'label_settings' ); // etc
 
// Clear up our settings
foreach ( $settingOptions as $settingName ) {
    delete_option( $settingName );
}