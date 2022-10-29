<?php



function custom_user_profile_fields($user){

  ?>

    <h3>Social Fields</h3><hr>

    <table class="form-table">

        <tr>

            <th><label for="website">Website</label></th>

            <td>

                <input type="text" class="regular-text" name="u_website" value="<?php echo esc_attr( get_the_author_meta( 'u_website', $user->ID ) ); ?>" id="u_website" /><br />

            </td>

        </tr>

        <tr>

            <th><label for="facebook_profile_url">Facebook Profile URL</label></th>

            <td>

                <input type="text" class="regular-text" name="u_facebook" value="<?php echo esc_attr( get_the_author_meta( 'u_facebook', $user->ID ) ); ?>" id="u_facebook" /><br />

            </td>

        </tr>

        <tr>

            <th><label for="instagram_profile_url">Instagaram Profile URL</label></th>

            <td>

                <input type="text" class="regular-text" name="u_instagram" value="<?php echo esc_attr( get_the_author_meta( 'u_instagram', $user->ID ) ); ?>" id="u_instagram" /><br />

            </td>

        </tr>

        <tr>

            <th><label for="linkedin_profile_url">Linkedin Profile URL</label></th>

            <td>

                <input type="text" class="regular-text" name="u_linkedin" value="<?php echo esc_attr( get_the_author_meta( 'u_linkedin', $user->ID ) ); ?>" id="u_linkedin" /><br />

            </td>

        </tr>

        <tr>

            <th><label for="myspace_profile_url">MySpace Profile URL</label></th>

            <td>

                <input type="text" class="regular-text" name="u_myspace" value="<?php echo esc_attr( get_the_author_meta( 'u_myspace', $user->ID ) ); ?>" id="u_myspace" /><br />

            </td>

        </tr>

        <tr>

            <th><label for="pinterest_profile_url">Pinterest Profile URL</label></th>

            <td>

                <input type="text" class="regular-text" name="u_pinterest" value="<?php echo esc_attr( get_the_author_meta( 'u_pinterest', $user->ID ) ); ?>" id="u_pinterest" /><br />

            </td>

        </tr>

        <tr>

            <th><label for="soundcloud_profile_url">SoundCloud Profile URL</label></th>

            <td>

                <input type="text" class="regular-text" name="u_soundcloud" value="<?php echo esc_attr( get_the_author_meta( 'u_soundcloud', $user->ID ) ); ?>" id="u_soundcloud" /><br />

            </td>

        </tr>

        <tr>

            <th><label for="tumblr_profile_url">Tumblr Profile URL</label></th>

            <td>

                <input type="text" class="regular-text" name="u_tumblr" value="<?php echo esc_attr( get_the_author_meta( 'u_tumblr', $user->ID ) ); ?>" id="u_tumblr" /><br />

            </td>

        </tr>

        <tr>

            <th><label for="twitter_user_name">Twitter User Name <b> (without @) </b></label></th>

            <td>

                <input type="text" class="regular-text" name="u_twitter" value="<?php echo esc_attr( get_the_author_meta( 'u_twitter', $user->ID ) ); ?>" id="u_twitter" /><br />

            </td>

        </tr>

        <tr>

            <th><label for="youtube_profile_url">Youtube Profile URL</label></th>

            <td>

                <input type="text" class="regular-text" name="u_youtube" value="<?php echo esc_attr( get_the_author_meta( 'u_youtube', $user->ID ) ); ?>" id="u_youtube" /><br />

            </td>

        </tr>

    </table>


<?php if(is_admin()){ ?>
<i>
    Shortcode: 
    [social_user social_handle="Above Social Handle with u_"/]
</i>
<?php } ?>



  <?php

}

add_action( 'show_user_profile', 'custom_user_profile_fields' );

add_action( 'edit_user_profile', 'custom_user_profile_fields' );

add_action( "user_new_form", "custom_user_profile_fields" );



function save_custom_user_profile_fields($user_id){

    # again do this only if you can

    if(!current_user_can('manage_options'))

        return false;



    # save my custom field

    update_usermeta($user_id, 'u_facebook', $_POST['u_facebook']);

    update_usermeta($user_id, 'u_instagram', $_POST['u_instagram']);

    update_usermeta($user_id, 'u_linkedin', $_POST['u_linkedin']);

    update_usermeta($user_id, 'u_myspace', $_POST['u_myspace']);

    update_usermeta($user_id, 'u_pinterest', $_POST['u_pinterest']);

    update_usermeta($user_id, 'u_soundcloud', $_POST['u_soundcloud']);

    update_usermeta($user_id, 'u_tumblr', $_POST['u_tumblr']);

    update_usermeta($user_id, 'u_twittwe', $_POST['u_twittwe']);

    update_usermeta($user_id, 'u_youtube', $_POST['u_youtube']);

    

    

}

add_action('user_register', 'save_custom_user_profile_fields');

add_action('profile_update', 'save_custom_user_profile_fields');