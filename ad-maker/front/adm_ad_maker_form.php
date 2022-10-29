<?php 



function adm_gift_finder_show_form($form){
	

	$form .= '<form class="adm_user_response" id="adm_user_response" method="post">';

	$form .= '<h2> '.get_option('form_headings').' </h2>';
	$form .= '<p style="font-size:17px;"> '.get_option('form_description').' </p>';

		global $wpdb;
    	$table_name = $wpdb->base_prefix.'adm_fields_table';
    	$query = "SELECT * FROM $table_name ORDER BY position ASC";
		$query_results = $wpdb->get_results($query);

		if(count($query_results) > 0) {

			foreach ($query_results as $key => $value) {

					$label = $value->label;
					$required = $value->required == "" ? "" : '<span class="required">*</span>';
					$required_attr = $value->required == "" ? "" : 'required';
					$type = $value->type;
					$name = $value->name;
					$className = $value->className;
					$values = unserialize($value->valuess);
					$multiple = $value->multiple == "" ? "" : "multiple";
					$placeholder = $value->placeholder;

					$nm = str_replace(' ', '_',  strtolower($label));

					$form.='<label for="'.$name.'" class="'.$name.' predefined" style="display:block;margin-top:10px;">'.$label.' '.$required.'</label>';

					if($type == "autocomplete"){

						$form .= '<input type="text"  name="'.$nm.'" placeholder="'.$placeholder.'" class="'.$className.'" '.$required_attr.' />';
					}

					if($type == "date"){
						$form .= '<input type="date"  name="'.$nm.'" placeholder="'.$placeholder.'" class="'.$className.'" '.$required_attr.' />';
					}

					if($type == "text"){
						$form .= '<input type="text"  name="'.$nm.'" placeholder="'.$placeholder.'" class="'.$className.'" '.$required_attr.' />';
					}

					if($type == "email"){

						$form .= '<input type="email"  name="'.$nm.'" placeholder="'.$placeholder.'" class="'.$className.'" '.$required_attr.' />';
					}

					if($type == "textarea"){
						$form .= '<textarea name="'.$nm.'" placeholder="'.$placeholder.'" class="'.$className.'" '.$required_attr.'></textarea>';
					}

					if($type == "number"){
						$form .= '<input type="number"  name="'.$nm.'" placeholder="'.$placeholder.'" class="'.$className.'" '.$required_attr.' />';
					}

					if($type == "radio-group"){
						foreach ($values as $k => $v) {
							$form .= '<label class="label--radio"> <input type="radio" name="'.$nm.'" '.$required_attr.' class="radio" value="'.sanitize_title($v->label).'" /> <span> '.$v->label.' </span> </label>';
						}
					}

					if($type == "checkbox-group"){

						/*echo "<pre>";
						print_r($values);
						echo "</pre>";*/

						foreach ($values as $k => $v) {

								//print_r($v);

								$selected = $v->selected == 1 ? "checked = '1' ": "";
								//echo $v->selected."<br>";
      							$form .= '<label class="label--checkbox"> <input type="checkbox" name="'.strtolower($label.'[]').'" '.$required_attr.' class="checkbox" value="'.sanitize_title($v->label).'" '.$selected.'/> <span> '.$v->label.' </span> </label>';
						}
					}

					if($type == "select"){
						$form .= '<select name="'.$nm.'"><option> Please select a service </option>';
						foreach ($values as $k => $v) {
							//$form .= '<li class="list__item">';
							$form.='<option value="'.$v->label.'">'.$v->label.'</option>'; 
    						//$form .= '</li>';
						}
						$form .= '</select>';
					}

					if($type == "paragraph"){
						$form .= '<input type="hidden" name="names" value="" class="Breakpoint">';
					}

					if($type == "file"){
						$form .= '<input type="file"  name="'.$nm.'" placeholder="'.$placeholder.'" class="'.$className.'" '.$required_attr.' '.$multiple.'/>';
					}

						//$form .= '<input type="hidden" name="AdID" value="'.$value->id.'"/>';
			}

			$form .= '

			  
            <div class="g-recaptcha" data-sitekey="'.get_option('adm_site_key').'"></div>

        	<div class="clear"></div>
        	<input type="submit" class="button" id="submit-button">';

		}else{
			$form .= '<h5> Please create form fields from dashboard to make plugin work. </h5>';
		}

	$form .= '</form>
	<div class="adm_response"></div>';
	return $form;
}

add_shortcode("ad_maker", "adm_gift_finder_show_form");