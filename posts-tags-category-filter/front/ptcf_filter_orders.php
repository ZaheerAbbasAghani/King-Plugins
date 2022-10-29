<?php 
add_action( 'wp_ajax_ptcf_filter_orders', 'ptcf_filter_orders' );
add_action( 'wp_ajax_nopriv_ptcf_filter_orders', 'ptcf_filter_orders' );
function ptcf_filter_orders() {

if($_POST['g_term']=="category" && $_POST['type']=="ALPHABETICAL"){
$category = $_POST['group'];

$args = array("post_type" => "post", "posts_per_page" => -1, "hide_empty" => 1, "category_name" => $category,'orderby'=>'title','order'=>'ASC');
$query = new WP_Query($args);	
$i=1;
if($query->have_posts()): while($query->have_posts()): $query->the_post();
		 if($i <=5){
        echo "<div class='ptcf_box' style='display:block;'><div class='innerBox'>";
    }else{
        echo "<div class='ptcf_box' style='display:none;'><div class='innerBox'>";
    }

    echo "<h3><a href='".get_the_permalink()."'> ".get_the_title().'</a></h3>';
    $content = preg_replace('#\[[^\]]+\]#', '',wp_trim_words(get_the_content(),50,'...'));
	$filters.="<p class='content'>".apply_filters('the_content', $content).'</p>';
	echo "<p style='text-transform:uppercase;'> BY: <a href='".esc_url(get_author_posts_url(get_the_author_meta('ID')) )."'>".get_the_author()."</a></p>";
	echo "<p>".get_the_date().'</p>';
	$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 
	echo "</div><div class='innerBoxImg'><a href='".get_the_permalink()."'> <img src='".$featured_img_url."'></a></div></div>";
		$i++;
	endwhile;
	else:
		echo "<p class='nothingFound'> Sorry, Nothing Found! </p>";
	endif;

}

if($_POST['g_term']=="category" && $_POST['type']=="MOST RECENT"){
$category = $_POST['group'];

$args = array("post_type" => "post", "posts_per_page" => -1, "hide_empty" => 1, "category_name" => $category,'orderby'=>'publish_date','order'=>'DSC');
$query = new WP_Query($args);	
$i=1;
if($query->have_posts()): while($query->have_posts()): $query->the_post();
		 if($i <=5){
        echo "<div class='ptcf_box' style='display:block;'><div class='innerBox'>";
    }else{
        echo "<div class='ptcf_box' style='display:none;'><div class='innerBox'>";
    }

    echo "<h3><a href='".get_the_permalink()."'> ".get_the_title().'</a></h3>';
    $content = preg_replace('#\[[^\]]+\]#', '',wp_trim_words(get_the_content(),50,'...'));
	$filters.="<p class='content'>".apply_filters('the_content', $content).'</p>';
	echo "<p style='text-transform:uppercase;'> BY: <a href='".esc_url(get_author_posts_url(get_the_author_meta('ID')) )."'>".get_the_author()."</a></p>";
	echo "<p>".get_the_date().'</p>';
	$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 
	echo "</div><div class='innerBoxImg'><a href='".get_the_permalink()."'> <img src='".$featured_img_url."'></a></div></div>";
		$i++;
	endwhile;
	else:
		echo "<p class='nothingFound'> Sorry, Nothing Found! </p>";
	endif;

}


if($_POST['g_term']=="tags" && $_POST['type']=="ALPHABETICAL"){

	$tag = $_POST['group'];
	$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $tag)));
	$args = array("post_type"=>"post","posts_per_page" => -1,"tag"=>$slug);
	$query = new WP_Query($args);	
	$i=1;
if($query->have_posts()): while($query->have_posts()): $query->the_post();
	 if($i <=5){
        echo "<div class='ptcf_box' style='display:block;'><div class='innerBox'>";
    }else{
        echo "<div class='ptcf_box' style='display:none;'><div class='innerBox'>";
    }

    echo "<h3><a href='".get_the_permalink()."'> ".get_the_title().'</a></h3>';
    $content = preg_replace('#\[[^\]]+\]#', '',wp_trim_words(get_the_content(),50,'...'));
	$filters.="<p class='content'>".apply_filters('the_content', $content).'</p>';
	echo "<p style='text-transform:uppercase;'> BY: <a href='".esc_url(get_author_posts_url(get_the_author_meta('ID')) )."'>".get_the_author()."</a></p>";
	echo "<p>".get_the_date().'</p>';
	$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 
	echo "</div><div class='innerBoxImg'><a href='".get_the_permalink()."'> <img src='".$featured_img_url."'></a></div></div>";
		$i++;
	endwhile;
	else:
		echo "<p class='nothingFound'> Sorry, Nothing Found! </p>";
	endif;
}


	
if($_POST['g_term']=="tags" && $_POST['type']=="MOST RECENT"){
	
	$tag = $_POST['group'];
	$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $tag)));
	$args = array("post_type" => "post", "posts_per_page" => -1,"tag"=>$slug,'orderby'=>'publish_date','order'=>'DSC');
	$query = new WP_Query($args);	
	$i=1;
if($query->have_posts()): while($query->have_posts()): $query->the_post();
	 if($i <=5){
        echo "<div class='ptcf_box' style='display:block;'><div class='innerBox'>";
    }else{
        echo "<div class='ptcf_box' style='display:none;'><div class='innerBox'>";
    }

    echo "<h3><a href='".get_the_permalink()."'> ".get_the_title().'</a></h3>';
    $content = preg_replace('#\[[^\]]+\]#', '',wp_trim_words(get_the_content(),50,'...'));
	$filters.="<p class='content'>".apply_filters('the_content', $content).'</p>';
	echo "<p style='text-transform:uppercase;'> BY: <a href='".esc_url(get_author_posts_url(get_the_author_meta('ID')) )."'>".get_the_author()."</a></p>";
	echo "<p>".get_the_date().'</p>';
	$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 
	echo "</div><div class='innerBoxImg'><a href='".get_the_permalink()."'> <img src='".$featured_img_url."'></a></div></div>";
		$i++;
	endwhile;
	else:
		echo "<p class='nothingFound'> Sorry, Nothing Found! </p>";
	endif;

}

wp_die();
}