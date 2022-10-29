<?php
/**
 * The admin-specific functionality of the plugin.
 * @author     Zaheer Abbas <aghanizaheer@gmail.com>
 */

//$panels = apply_filters( 'wpcf7_editor_panels', $panels );
add_filter( 'wpcf7_editor_panels', function($panels) {
    $panels['new-setting-panel'] = array(
            'title' => __( 'Twilio SMS', 'contact-form-7' ),
            'callback' => 'wpcf7_editor_panel_new_setting'
    );
    return $panels;
}, 10, 1 );

function wpcf7_editor_panel_new_setting($form)
{

if ( wpcf7_admin_has_edit_cap() ) {
  $options = get_option( 'wpcf7_international_sms_' . (method_exists($form, 'id') ? $form->id() : $form->id()) );
  
  if( empty( $options ) || !is_array( $options ) ) {
    $options = array( 'sms_to_numbers' => '', 'sms_from_numbers' => '', 'message' => '', 'adminNumber' => '', 'visitorNumber' => '','visitorMessage' => '','permission'=>'');
  }
  $options['form'] = $form;
  $data =  $options;  
  //include(plugins_url('', __FILE__ ).'/cf7-template.php'); ?>

<div id="cf7si-sms-sortables" class="meta-box-sortables ui-sortable">
    <h3><?php echo "Twilio SMS"; ?></h3>
    <div class="box1">
    <fieldset>
        <legend><?php echo "In the following fields, you can use these tags:"; ?>
            <br />
            <?php  $data['form']->suggest_mail_tags(); ?>
        </legend>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="wpcf7-sms-recipient"><?php _e("SMS To Numbers:",'Contact-Form-7'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="wpcf7-sms-recipient" name="wpcf7si-settings[sms_to_numbers]" class="wide" size="70" value="<?php echo $data['sms_to_numbers']; ?>">
                        <br/> <?php _e("<small>Enter Numbers By <code>,</code> for multiple</small>",'Contact-Form-7'); ?>
                    </td>
                </tr>

                 <tr>
                    <th scope="row">
                        <label for="wpcf7-sms-recipient"><?php _e("From Number:",'Contact-Form-7'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="wpcf7-sms-recipient" name="wpcf7si-settings[sms_from_numbers]" class="wide" size="70" value="<?php echo esc_attr( get_option('twilio_phone_number') ); ?>">
                        <br/> <?php _e("<small>Sender number showing from settings </small>",'Contact-Form-7'); ?>
                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="wpcf7-mail-body"><?php _e("Message body:",'Contact-Form-7'); ?></label>
                    </th>
                    <td>
                        <textarea id="wpcf7-mail-body" name="wpcf7si-settings[message]" cols="100" rows="6" class="large-text code"><?php echo $data['message']; ?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </fieldset>
    <hr/>
	</div><!-- Box -->
<h3><label> Allow Visitor SMS </label> 
    <input type="checkbox" name="allow_visitors" id="allow_visitors" <?php if(@$data['permission']=="Yes"){echo "checked"; }else{ echo ""; } ?>>
    <input type="hidden" name="wpcf7si-settings[permission]" id="permission" value="<?php echo @$data['permission']; ?>">
</h3>

    <div class="fieldsetwrap" style="display: none;">
    <h3><?php _e("Visitor SMS Notifications",'Contact-Form-7'); ?></h3>
    <fieldset>
        <legend><?php _e("In the following fields, you can use these tags:",'Contact-Form-7'); ?>
            <br />
            <?php $data['form']->suggest_mail_tags(); ?>
        </legend>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="wpcf7-sms-recipient"><?php _e("Visitor Mobile: ",'Contact-Form-7'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="wpcf7-sms-recipient" name="wpcf7si-settings[visitorNumber]" class="wide" size="70" value="<?php echo @$data['visitorNumber']; ?>">
                        <br/> <?php _e("<small>Use <b>Contact_Form Tags</b> To Get Visitor Mobile Number</small>",'Contact-Form-7');?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="wpcf7-sms-recipient"><?php _e("From Number: ",'Contact-Form-7'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="wpcf7-sms-recipient" name="wpcf7si-settings[adminNumber]" class="wide" size="70" value="<?php echo esc_attr( get_option('twilio_phone_number') ); ?>">
                        <br/> <?php _e("<small>Sender number showing from settings </small>",'Contact-Form-7');?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="wpcf7-mail-body"><?php _e("Message body:",'Contact-Form-7'); ?></label>
                    </th>
                    <td>
                        <textarea id="wpcf7-mail-body" name="wpcf7si-settings[visitorMessage]" cols="100" rows="6" class="large-text code"><?php echo @$data['visitorMessage']; ?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </fieldset>
    </div><!--fieldsetwrap-->
</div>


<?php 

}


}

// Saving message form data

function save_form( $form ) {
    update_option( 'wpcf7_international_sms_' . (method_exists($form, 'id') ? $form->id() : $form->id()), $_POST['wpcf7si-settings'] );
} 
add_action( 'wpcf7_after_save', 'save_form'  );
?>