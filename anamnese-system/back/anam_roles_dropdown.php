<?php
//1. Add a new form element...
add_action( 'register_form', 'anam_register_form' );
function anam_register_form() {

    global $wp_roles;
    echo "<label>"._e("Role")."</label>";
    echo '<select name="role" class="input">';
    foreach ( $wp_roles->roles as $key=>$value ) {
    print_r($value);
        echo '<option value="'.$key.'">'.$value['name'].'</option>';
   
    }
    echo '</select>';
}

//2. Add validation.
add_filter( 'registration_errors', 'anam_registration_errors', 10, 3 );
function anam_registration_errors( $errors, $sanitized_user_login, $user_email ) {

    if ( empty( $_POST['role'] ) || ! empty( $_POST['role'] ) && trim( $_POST['role'] ) == '' ) {
         $errors->add( 'role_error', __( '<strong>ERROR</strong>: You must include a role.', 'mydomain' ) );
    }

    return $errors;
}

//3. Finally, save our extra registration user meta.
add_action( 'user_register', 'anam_user_register' );
function anam_user_register( $user_id ) {

   $user_id = wp_update_user( array( 'ID' => $user_id, 'role' => $_POST['role'] ) );
}

// Redirect users by role wordpress

function anam_login_redirect( $redirect_to, $request, $user ) {
    //is there a user to check?
    global $user;
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {

        if ( in_array( 'administrator', $user->roles ) ) {
            // redirect them to the default place
            $data_login = home_url().'/wp-admin';
            //print_r($data_login);
            return $data_login;
        } else {
            return home_url().'/my-account';
        }
    } else {
        return home_url().'/wp-admin';
    }
}
add_filter( 'login_redirect', 'anam_login_redirect', 10, 3 );


