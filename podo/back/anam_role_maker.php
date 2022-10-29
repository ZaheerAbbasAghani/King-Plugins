<?php 

add_role('mitarbeiter', __(
 'Mitarbeiter'),
 	  array(
     'read'            => true, // Allows a user to read
     'create_posts'      => true, // Allows user to create new posts
     'edit_posts'        => true, 
     'edit_others_posts' => true, 
     'publish_posts' => true, // Allows the user to publish posts
     'manage_categories' => true, 
    )
);