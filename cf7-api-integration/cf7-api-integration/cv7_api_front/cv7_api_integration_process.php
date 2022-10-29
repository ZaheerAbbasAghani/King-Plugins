<?php

function abc(){

	//get_post_meta( 1, '', false );
	//echo "Here World".get_post_meta( 1, 'street');
	echo "Here World";

}
add_action( "init", "abc");