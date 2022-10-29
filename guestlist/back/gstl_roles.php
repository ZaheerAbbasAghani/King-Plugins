<?php 

/* Create DJ User Role */
add_role(
    'dj-employee', //  System name of the role.
    __( 'DJ/Employee'  ), // Display name of the role.
    array(
        'read' => true,
        'edit_pages' => true,
        'edit_posts' => true,
        'edit_published_pages' => true,
        'edit_published_posts' => true,
        'edit_others_pages' => true,
        'edit_others_posts' => true,
        'publish_pages' => true,
        'publish_posts' => true,
        'upload_files' => true,
        'unfiltered_html' => true
    )
);