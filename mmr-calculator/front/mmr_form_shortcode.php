<?php

function mmr_api_process($form){

	$form = "";

	$form .= "<div class='mmr_calculator'> 

		<h3> What Is My MMR Calculator </h3>
		<p> Select The Region of Your LoL Account </p>

		<form method='post' action='' id='mmr_calculator'> 

			<ul class='region-list'>
			  <li>
			    <input type='radio' id='EUW' value='EUW' name='region' checked/>
			    <label for='EUW'>EUW</label>
			  </li>

			  <li>
			    <input type='radio' id='NA' value='NA' name='region' />
			    <label for='NA'>NA</label>
			  </li>

			  <li>
			    <input type='radio' id='EUNE' value='EUNE' name='region' />
			    <label for='EUNE'>EUNE</label>
			  </li>

			  <li>
			    <input type='radio' id='KR' value='KR' name='region' />
			    <label for='KR'>KR</label>
			  </li>
			 
			</ul>

			<div class='summoner_extra_field'>
				<input type='text' id='summoner_name' name='id='summoner_name' placeholder='Your Summoner Name'>
				<input type='submit' value='submit'>
			</div>

		</form>

		<div class='mmr_response'></div>
	</div>";




	return $form;
	
}
add_shortcode("mmr","mmr_api_process");