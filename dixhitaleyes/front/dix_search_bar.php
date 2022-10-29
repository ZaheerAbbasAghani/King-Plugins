<?php 

function dixSearch_form($form){

	$search_page = get_the_permalink( get_option('dix_search_result_page')['page_id'] );

	$form='<form role="search" method="get" id="searchform" action="'.$search_page.'">
	    <div>
	    	<label class="screen-reader-text" for="s">' . __('Search for:') . '</label>
	    	<input type="text" value="" name="dixQuery" id="dixQuery" placeholder="Search..."/>

			<button class="elementor-search-form__submit" type="submit" title="Search" aria-label="Search">
					<i aria-hidden="true" class="fas fa-search"></i>							<span class="elementor-screen-only">Search</span>
			</button>


	    </div>
    </form>';

	return $form;
}
add_shortcode("dixSearchForm", "dixSearch_form");