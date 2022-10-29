<?php
function ptcf_show_filters($filters){

$filters .= '<div class="ptcf_container">
<div class="example">
    <div class="menu">
            <ul id="nav">
                <li><span> <b> FILTER BY: </b></span></li>
                <li><a href="#">CATEGORY</a>
                    <div class="subs">
                        <div>
                            <ul>
                                <li>
                                    <ul>';
	if( $terms = get_terms( array(
    'taxonomy' => 'category', // to make it simple I use default categories
    'orderby' => 'name'
) ) ) : 
	// if categories exist, display the dropdown
	foreach ( $terms as $term ) :
		$filters.=  '<li><a href="#" term-id="' . $term->term_id . '" group="category"> ' . $term->name . '</a></li>'; 
	
	endforeach;
	
endif;
$filters.= ' </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li><a href="#">TAGS</a>
                    <div class="subs">
                        <div class="wrp2">
                            <ul>
                                <li>
                                    <ul>';
$tags = get_tags(array(
  'hide_empty' => false
));
foreach ($tags as $tag) {
  $filters.= '<li><a href="#" term-id="' . $tag->name . '"  group="tags"> ' . $tag->name . '</a></li>';
}	
                                      
                                   $filters .= '</ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                   <li><a href="#">ORDER BY</a>
                    <div class="subs">
                        <div class="wrp2">
                            <ul>
                                <li>
                                    <ul>
<li><a href="#" term-id="alphabetical" group="orderby" class="orders">ALPHABETICAL</a></li>
<li><a href="#" term-id="most-recent" group="orderby" class="orders">MOST RECENT</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
    </div></div>';
	
	
	
$filters .= '</div>';

$filters .= '<div class="choosenValue"></div>';
$filters .= '<div class="listofposts">';

$args = array("post_type" => "post", "posts_per_page" => -1, "hide_empty" => 1);
$query = new WP_Query($args);	
$i=0;
if($query->have_posts()): while($query->have_posts()): $query->the_post();
	
    if($i <=5){
        $filters .= "<div class='ptcf_box' style='display:block;'><div class='innerBox'>";
    }else{
        $filters .= "<div class='ptcf_box' style='display:none;'><div class='innerBox'>";
    }

    $filters .= "<h3><a href='".get_the_permalink()."'> ".get_the_title().'</a></h3>';
    $content = preg_replace('#\[[^\]]+\]#', '',wp_trim_words(get_the_content(),50,'...'));
	$filters.="<p class='content'>".apply_filters('the_content', $content).'</p>';
	$filters .= "<p style='text-transform:uppercase;'> BY: <a href='".esc_url(get_author_posts_url(get_the_author_meta('ID')) )."'>".get_the_author()."</a></p>";
	$filters .= "<p>".get_the_date().'</p>';
	$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 
	$filters .= "</div><div class='innerBoxImg'><a href='".get_the_permalink()."'> <img src='".$featured_img_url."'></a></div></div>";
	$i++;
endwhile;
endif;

$filters .=  "</div>";

$filters .= "<a href='#' class='misha_loadmore'> LOAD 5 MORE RESULTS </a>";

return $filters;
}
add_shortcode("posts_filters", "ptcf_show_filters" );