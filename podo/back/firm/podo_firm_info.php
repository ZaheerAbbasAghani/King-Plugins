<div class="wrap" style="background: #fff;padding: 10px 20px;">
<h1>Firmeninformation </h1><hr>



<form method="post" action="options.php" id="podo_firm">
    <?php settings_fields( 'podo-plugin-firm-group' ); ?>
    <?php do_settings_sections( 'podo-plugin-firm-group' ); ?>
    <h3>Allgemeine Informationen</h3><hr><br>
    <ul class="firm_information">

        <li><label> Firmenname </label><input type="text" name="podo_company_name" value="<?php echo esc_attr( get_option('podo_company_name') ); ?>" /></li>
        
        <li><label> Firmenlogo </label><input type="hidden" name="podo_company_logo" value="<?php echo esc_attr(get_option('podo_company_logo') ); ?>" /><img src="<?php echo esc_attr(get_option('podo_company_logo') ); ?>" id="preview_logo" /><button class="button podo-file-upload">hochladen</button></li>

         
        <li><label> Adresse & Hausnummer </label>
            <textarea cols="5" rows="5" name="podo_address_number"><?php echo esc_attr( get_option('podo_address_number') ); ?>
            </textarea>
        </li>

        
        <li><label>Postleitzahl</label><input type="text" name="podo_zipcode" value="<?php echo esc_attr( get_option('podo_zipcode') ); ?>" /></li>

        
        <li><label>Stadt</label><input type="text" name="podo_city" value="<?php echo esc_attr( get_option('podo_city') ); ?>" /></li>

        
        <li><label>Website</label><input type="text" name="podo_website" value="<?php echo esc_attr( get_option('podo_website') ); ?>" /></li>
              
        <li><label>Telefon</label><input type="text" name="podo_phone" value="<?php echo esc_attr( get_option('podo_phone') ); ?>" /></li>
            
        <li><label>Mobil</label><input type="text" name="podo_mobile" value="<?php echo esc_attr( get_option('podo_mobile') ); ?>" /></li>
        
       <h3> Bank Informationen </h3>
       <hr><br>
        
        
        <li><label>Name der Bank</label><input type="text" name="podo_bank_name" value="<?php echo esc_attr( get_option('podo_bank_name') ); ?>"/></li>

        
        <li><label>IBAN</label><input type="text" name="podo_iban" value="<?php echo esc_attr( get_option('podo_iban') ); ?>"/></li>

        <li><label> Währung </label>

		<select name="podo_currency">
		  <option selected="selected" disabled="disabled">Select a currency </option>
		  <option value="€" <?php selected(get_option('podo_currency'), "€"); ?>>€</option>
		  <option value="CHF" <?php selected(get_option('podo_currency'), "CHF"); ?>>CHF</option>
		
		</select>
        </li>

        <li><label>Name des Kontos (Firma oder Inhaber) </label><input type="text" name="podo_name_of_account" value="<?php echo esc_attr( get_option('podo_name_of_account') ); ?>" /></li>

        <li><label> BIC </label><input type="text" name="podo_bic" value="<?php echo esc_attr( get_option('podo_bic') ); ?>" /></li>

       
    </ul>
    
    <?php submit_button(); ?>

</form>


</div>