<?php

function add_login_member_caps() {
    // gets the author role
     $role = get_role( 'login_member' );
    // This only works, because it accesses the class instance.
    // would allow the author to edit others' posts for current theme only
    //$role->remove_cap( 'bethany_access_full_message' ); 
    //$role->remove_cap( 'bethany_access_sstt' );
	$role->remove_cap( 'read' );
    $role->remove_cap( 'edit_posts' );
    $role->remove_cap( 'delete_posts' );
	$role->add_cap( 'bethany_access_full_message' );
	// echo '<pre>'.print_r($role, 1).'</pre>';
}

function add_sst_caps() {
	// gets the author role
	$role = get_role( 'sst' );
	$role->remove_cap( 'read' );
	$role->remove_cap( 'edit_posts' );
	$role->remove_cap( 'delete_posts' );
    $role->add_cap( 'bethany_access_full_message' );
	$role->add_cap( 'bethany_access_sst' );
}

function add_pastor_caps() {
    // gets the author role
    $role = get_role( 'sst' );
    $role->remove_cap( 'read' );
    $role->remove_cap( 'edit_posts' );
    $role->remove_cap( 'delete_posts' );
    $role->add_cap( 'bethany_access_full_message' );
    $role->add_cap( 'bethany_access_sst' );
    $role->add_cap( 'bethany_access_pastor' );
}

add_action( 'admin_init', 'add_login_member_caps');
add_action( 'admin_init', 'add_sst_caps');
add_action( 'admin_init', 'add_pastor_caps');

// Add Role login_member (Post Login)
// Login Member Should not have any capability by default
add_role('login_member', 'Login member', array(
    'read' => false,
    'edit_posts' => false,
    'delete_posts' => false,
));

// Add Role sstt (Sunday School Teachers Training)
// Sunday School Teachers Training should not have any capability by default
add_role('sst', 'Sunday School Teacher', array(
	'read' => false,
	'edit_posts' => false,
	'delete_posts' => false,
));

// Add Role pastor (Pastor)
// Sunday School Teachers Training should not have any capability by default
add_role('pastor', 'Pastor', array(
    'read' => false,
    'edit_posts' => false,
    'delete_posts' => false,
));

function hide_admin_bar_from_login_members() {
	if ( current_user_can('bethany_access_full_message')  ) {
		add_filter('show_admin_bar', '__return_false');
	}
}

function hide_admin_bar_from_sst() {
	if ( current_user_can('bethany_access_sst') ) {
		add_filter('show_admin_bar', '__return_false');
	}
}

function hide_admin_bar_from_pastor() {
    if ( current_user_can('bethany_access_pastor') ) {
        add_filter('show_admin_bar', '__return_false');
    }
}

add_action( 'init', 'hide_admin_bar_from_login_members', 100);
add_action( 'init', 'hide_admin_bar_from_sst', 100);
add_action( 'init', 'hide_admin_bar_from_pastor', 100);
