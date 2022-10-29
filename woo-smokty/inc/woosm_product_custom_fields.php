<?php

function kia_custom_option(){
    echo  '<div class="woosm"><p><label>Enter Width <input type="number" class="custom_measure" name="_custom_width" id="_custom_width" value="" placeholder="Range: Choose 24 - 144" min="24" max="144" required/></label></p>';

    echo  '<p><label>Enter Height <input type="number" class="custom_measure" name="_custom_height" id="_custom_height" value="" placeholder="Range: Choose 48 - 144" min="48" max="144" required/></label></p></div>';

    echo  '<p id="ac" style="display:none;"><label>Aluminum Castte <input type="checkbox" class="aluminum_castte" name="aluminum_castte" id="aluminum_castte" required/></label></p></div>';
}
add_action( 'woocommerce_before_add_to_cart_button', 'kia_custom_option', 9 );