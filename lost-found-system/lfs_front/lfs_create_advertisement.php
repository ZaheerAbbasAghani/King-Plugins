<?php

function lfs_create_advertisement_form($adv){
$image = plugin_dir_url(dirname(__FILE__));
$adv .= '<form method="post" id="lfs_advertisement_form" enctype="multipart/form-data"> 
		<div class="user_info">
			<h6> Your contact Information </h6>
		  <div class="form-group lfs_box">
		    <label for="user_name">Name</label>
		    <input type="text" class="form-control" name="lfs_user_name" id="lfs_user_name" placeholder="Enter your name">
		  </div>
		 
		  <div class="form-group">
		    <label for="user_email"> Email</label>
		    <input type="text" class="form-control" id="lfs_user_email" name="lfs_user_email" placeholder="Enter your email address">
		  </div>

		  <div class="form-group">
		    <label for="phone_number"> Phone Number </label>
		    <input type="text" class="form-control" id="lfs_phone_number" name="lfs_phone_number" placeholder="Enter your phone number">
		  </div>
		</div>

		<div class="user_info">
			<h6> Missing Information </h6>
		  <div class="form-group">
		    <label for="lost_or_found_date"> Date </label>
		    <input type="date" class="form-control" id="lfs_lost_or_found_date" name="lfs_lost_or_found_date" placeholder="Enter date here">
		  </div>

		  
		  <div class="form-group">
		    <label for="lost_or_found_place"> Place </label>
		    <input type="text" class="form-control" id="lfs_lost_or_found_place" name="lfs_lost_or_found_place" placeholder="Enter place here">
		  </div>

		  <div class="form-group">
		    <label for="state"> State </label>
		    <input type="text" class="form-control" id="lfs_state" name="lfs_state" placeholder="Enter state here">
		  </div>

		  <div class="form-group">
		    <label for="animalType"> Animal Type </label>
		    <select class="form-control" id="lfs_animal_type" name="lfs_animal_type">
		    	<option value=""> Select Animal Type </option>
		    	<option value="dog">Dog</option>
		    	<option value="cat">Cat</option>
		    </select>
		  </div>

		  <div class="form-group">
		    <label for="Animal Breed"> Animal Breed </label>
		    <input type="text" class="form-control" id="lfs_animal_breed" name="lfs_animal_breed" placeholder="Enter animal breed">
		  </div>

		  <div class="form-group">
		    <label for="Special Mark"> Special Mark </label>
		    <input type="text" class="form-control" id="lfs_special_mark" name="lfs_special_mark" placeholder="Enter special mark here">
		  </div>

		  <div class="form-group">
		    <label for="Birth Day"> Birth Day </label>
		    <input type="date" class="form-control" id="lfs_birth_Day" name="lfs_birth_Day">
		  </div>

		  <div class="form-group">
		    <label for="Micro Chip"> Micro Chip </label>
		    <input type="text" class="form-control" id="lfs_micro_chip" name="lfs_micro_chip" placeholder="Enter micro chip here">
		  </div>

		  <div class="form-group">
		    <label for="lfs_pictures">Pictures Upto 5</label>
		    <input type="file" class="form-control" id="lfs_pictures" name="lfs_pictures" multiple>
		  </div>

		  <div class="form-group">
		    <label for="Description"> Description </label>
		    <textarea class="form-control" id="lfs_description" name="lfs_description" cols="10" rows="3" placeholder="Enter description here"></textarea>
		  </div>
		</div>

		 <button type="submit" class="btn btn-primary lfs_frm">Submit</button>
		 <img src="'.$image.'/assets/loaders.gif" style="width:40px;display:none;" class="showaftersubmit"/>
		</form>';

return $adv;

}
add_shortcode("create_ad","lfs_create_advertisement_form");