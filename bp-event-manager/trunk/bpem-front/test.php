<?php
/*
function add_iuda_tab() {
global $bp;

bp_core_new_subnav_item(array(

'name' => 'test',
'slug' => 'test',
'parent_slug' => $bp->groups->current_group->slug,
'parent_url' => bp_get_group_permalink($bp->groups->current_group),
//'parent_slug' => $bp->profile->slug,
'screen_function' => 'iuda_screen',
'position' => 200,
'default_subnav_slug' => 'test',
));

}
add_action('bp_setup_nav', 'add_iuda_tab', 100);

function iuda_screen() {
add_action('bp_template_title', 'iuda_screen_title');
add_action('bp_template_content', 'iuda_screen_content');
bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
}

function iuda_screen_title() {
echo 'iuda Title<br/>';
}

function iuda_screen_content() {
echo 'iuda Content<br/>';
}

/*
function profile_new_nav_item() {

global $bp;

bp_core_new_nav_item(
array(
'name' => 'Extra Tab',
'slug' => 'extra_tab',
'default_subnav_slug' => 'extra_sub_tab', // We add this submenu item below
'screen_function' => 'view_manage_tab_main',
)
);
}

add_action('wp', 'profile_new_nav_item', 10);

function view_manage_tab_main() {
add_action('bp_template_content', 'bp_template_content_main_function');
bp_core_load_template('template_content');
}

function bp_template_content_main_function() {
//echo "Main tab";
}

function profile_new_subnav_item() {
global $bp;
$parent_slug = 'extra_tab';
bp_core_new_subnav_item(array(
'name' => 'Extra Sub Tab',
'slug' => 'extra_sub_tab',
'parent_url' => trailingslashit(bp_displayed_user_domain() . 'extra_tab'),
'parent_slug' => $parent_slug,
'position' => 10,
'screen_function' => 'view_manage_sub_tab_main',
));
}

add_action('bp_setup_nav', 'profile_new_subnav_item', 10);

function view_manage_sub_tab_main() {
add_action('bp_template_content', 'bp_template_content_sub_function');
bp_core_load_template('template_content');
}

function bp_template_content_sub_function() {
global $bp;
echo "here";
echo $bp->loggedin_user->domain . $parent_slug . '/';
echo bp_displayed_user_domain();
}

function profile_new_subnav_item_2() {
global $bp;
$parent_slug = 'extra_tab';
bp_core_new_subnav_item(array(
'name' => 'Extra Sub Tab 2',
'slug' => 'extra_sub_tab2',
'parent_url' => trailingslashit(bp_displayed_user_domain() . 'extra_tab'),
'parent_slug' => $parent_slug,
'position' => 10,
'screen_function' => 'view_manage_sub_tab_main_2',
));
}

add_action('bp_setup_nav', 'profile_new_subnav_item_2', 10);

function view_manage_sub_tab_main_2() {
add_action('bp_template_content', 'bp_template_content_sub_function_2');
bp_core_load_template('template_content');
}

function bp_template_content_sub_function_2() {
global $bp;

//echo "here";
//echo $bp->loggedin_user->domain . $parent_slug . '/';
echo bp_displayed_user_domain();
}

/*
function add_animal_tabs() {
global $bp;

bp_core_new_nav_item(array(
'name' => 'Animals',
'slug' => 'animals',
'parent_url' => $bp->displayed_user->domain,
'parent_slug' => $bp->profile->slug,
'screen_function' => 'animals_screen',
'position' => 200,
'default_subnav_slug' => 'animals',
));

bp_core_new_subnav_item(array(
'name' => 'Dogs',
'slug' => 'dogs',
'parent_url' => trailingslashit(bp_displayed_user_domain() . 'animals'),
'parent_slug' => 'animals',
'screen_function' => 'dogs_screen',
'position' => 100,
'user_has_access' => bp_is_my_profile(),
));

bp_core_new_subnav_item(array(
'name' => 'Cats',
'slug' => 'cats',
'parent_url' => trailingslashit(bp_displayed_user_domain() . 'animals'),
'parent_slug' => 'animals',
'screen_function' => 'cats_screen',
'position' => 150,
'user_has_access' => bp_is_my_profile(),
));

}
add_action('bp_setup_nav', 'add_animal_tabs', 100);

function animals_screen() {
add_action('bp_template_title', 'animals_screen_title');
add_action('bp_template_content', 'animals_screen_content');
bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
}
function animals_screen_title() {
echo 'Animals Title<br/>';
}
function animals_screen_content() {
echo 'Animals Content<br/>';
}

function dogs_screen() {
add_action('bp_template_content', 'dogs_screen_content');
bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
}
function dogs_screen_content() {
echo 'Dogs';
}

function cats_screen() {
add_action('bp_template_content', 'cats_screen_content');
bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
}
function cats_screen_content() {
echo 'Cats';
}
 */

/*function bpem_events_tab_test() {

global $bp;

if (isset($bp->groups->current_group->slug)) {

bp_core_new_subnav_item(array(

'name' => 'Animals',

'slug' => 'animals',

'parent_slug' => $bp->groups->current_group->slug,

'parent_url' => bp_get_group_permalink($bp->groups->current_group),

'screen_function' => 'my_new_group_show_screen_test',

'position' => 80));

}

}

add_action('wp', 'bpem_events_tab_test');

function my_new_group_show_screen_test() {

add_action('bp_template_content', 'bpem_group_show_screen_content_test');

$templates = array('groups/single/plugins.php', 'plugin-template.php');

if (strstr(locate_template($templates), 'groups/single/plugins.php')) {

bp_core_load_template(apply_filters('bp_core_template_plugin', 'groups/single/plugins'));

} else {

bp_core_load_template(apply_filters('bp_core_template_plugin', 'plugin-template'));

}

}

function bpem_group_show_screen_content_test() {
global $bp;
echo $bp->groups->current_group->slug;
echo bp_get_group_permalink($bp->groups->current_group);
}*/