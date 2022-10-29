<?php 
function create_user_from_registration($cfdata) {
    if (!isset($cfdata->posted_data) && class_exists('WPCF7_Submission')) {
        // Contact Form 7 version 3.9 removed $cfdata->posted_data and now
        // we have to retrieve it from an API
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $formdata = $submission->get_posted_data();
        }
    } elseif (isset($cfdata->posted_data)) {
        // For pre-3.9 versions of Contact Form 7
        $formdata = $cfdata->posted_data;
    } else {
        // We can't retrieve the form data
        return $cfdata;
    }
    // Check this is the user registration form
    if ( $cfdata->title() == 'Excessive Sweating Consultation Form') {
        $password = wp_generate_password( 12, false );
        $email = $formdata['your-email'];
        $name = $formdata['your-fname'];
        // Construct a username from the user's name
        $username = strtolower(str_replace(' ', '', $name));
        $name_parts = explode(' ',$name);
        if ( !email_exists( $email ) ) {
            // Find an unused username
            $username_tocheck = $username;
            $i = 1;
            while ( username_exists( $username_tocheck ) ) {
                $username_tocheck = $username . $i++;
            }
            $username = $username_tocheck;
            // Create the user
            $userdata = array(
                'user_login' => $username,
                'user_pass' => $password,
                'user_email' => $email,
                'nickname' => reset($name_parts),
                'display_name' => $name,
                'first_name' => reset($name_parts),
                'last_name' => end($name_parts),
                'role' => 'customer'
            );
            $user_id = wp_insert_user( $userdata );
            if ( !is_wp_error($user_id) ) {
                // Email login details to user
                $admin_email = get_option( 'admin_email' );
                //$admin_email = "aghanizaheer@gmail.com";
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
                $message = "Thanks for filling consultation form we'll respond as quick as possible:"."\r\n";
                $message .= sprintf(__('Username: %s'), $username) . "\r\n";
                $message .= sprintf(__('Password: %s'), $password) . "\r\n";
                $message .= wp_login_url() . "\r\n";
                wp_mail($admin_email, "Request a consultation had made on site.", $message);
            }
        }
    }
    return $cfdata;
}
add_action('wpcf7_before_send_mail', 'create_user_from_registration', 1);