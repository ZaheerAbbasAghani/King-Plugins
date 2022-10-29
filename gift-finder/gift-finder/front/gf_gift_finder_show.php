<?php 

function gf_gift_finder_show_form($form){

	$form .= '<form class="wep_user_response" id="wep_user_response" method="post">';
			

		global $wpdb;
    	$table_name = $wpdb->base_prefix.'gf_fields_table';

    	$query = "SELECT * FROM $table_name  WHERE dependson_code='' ";
		$query_results = $wpdb->get_results($query);
		if(count($query_results) > 0) {

			$form .=  '<ul class="list">';

			foreach ($query_results as $key => $value) {
				
				$form .= '<fieldset class="personal-data '.$key.'">';
						
					$label = $value->label;
					$required = $value->required == "" ? "" : '<span class="required">*</span>';
					$required_attr = $value->required == "" ? "" : 'required';
					$type = $value->type;
					$name = $value->name;
					$className = $value->className;
					$values = unserialize($value->valuess);

					$dependson_type = $value->dependson_type;
					$dependson_code = $value->dependson_code;
	
					$form.='<label for="'.$name.'" class="'.$name.'">'.$label.' '.$required.'</label>';
					
					if($type == "text"){
						$form .= '<input type="text"  name="'.$name.'" placeholder="'.$label.'" class="'.$className.'" '.$required_attr.' data-code="'.$dependson_code.'"/>';
					}

					if($type == "radio-group"){
		
						foreach ($values as $k => $v) {
							$form .= '<li class="list__item">
      							<label class="label--radio"> <input type="radio" name="'.$name.'" '.$required_attr.' class="radio" value="'.sanitize_title($v->label).'" data-code="'.$dependson_code.'"/> <span> '.$v->label.' </span> </label>
    						</li>';
						}
					}

					if($type == "checkbox-group"){
						foreach ($values as $k => $v) {
							$form .= '<li class="list__item">
      							<label class="label--checkbox"> <input type="checkbox" name="'.$name.'[]" '.$required_attr.' class="checkbox" value="'.sanitize_title($v->label).'" data-code="'.$dependson_code.'"/> <span> '.$v->label.' </span> </label>
    						</li>';
						}
					}

					if($type == "paragraph"){
						$form .= '<input type="hidden" name="names" value="" class="Breakpoint">';
					}

				$form .= '</fieldset>';

			}

			$form .=  "</ul>";
			
		}

	$form .= '
        	<div class="clear"></div>
		</form>

	<div class="gf_response"></div>';

	return $form;

}
add_shortcode("gift_finder", "gf_gift_finder_show_form");