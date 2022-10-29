<?php 



function zci_search_zip_codes_frontend($search){



	$search .= "<div class='zci_search_front' style='text-align:center; margin-bottom:10px;'>

			<input type='text' name='zci_search_box' class='zci_search_box' placeholder='Enter Zip Code' maxlength='5' style='padding: 10px;border: 1px solid #ddd;border-radius: 6px;width: 70%;margin: auto;display: block;'>

			<input type='submit' name='zci_search_btn' id='zci_search_btn' class='zci_search' value='Check Availability' style='background:rgb(234, 138, 153);border: none;padding: 10px 15px;margin-top: 10px;font-size: 14px;border-radius: 4px;color: #fff;opacity:0.6'>

			<div id='zci_message'></div>

	 </div>";



	 return $search;

}



add_shortcode( "zip_code_search", "zci_search_zip_codes_frontend");



?>