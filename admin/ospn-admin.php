<?php

if (!class_exists('OSPN_Members_List_Table')) {
    require_once('includes/class-ospn-members-list-table.php');
}

/**
 *
 */
function ospn_admin_members() {
	ob_start();
	require(dirname(__FILE__) . '/views/members.php');
	$output = ob_get_clean();
	echo $output;
}

/**
 *
 */
function ospn_admin_member_new() {
    ob_start();
    require(dirname(__FILE__) . '/views/member-new.php');
    $output = ob_get_clean();
    echo $output;
}

/**
 *
 */
function ospn_admin_add_members_options() {
    global $wp_list_table;
    $option = 'per_page';
    $args = array(
        'label' => 'Members',
        'default' => 10,
        'option' => 'members_per_page'
    );
    add_screen_option($option, $args);
    $wp_list_table = new OSPN_Members_List_Table();
}

/**
 * @param $status
 * @param $option
 * @param $value
 * @return mixed
 */
function ospn_admin_set_members_option($status, $option, $value) {
    return $value;
}

add_filter('set-screen-option', 'ospn_admin_set_members_option', 10, 3);

/**
 *
 */
function ospn_admin_add_menu() {
	 if (current_user_can('manage_options')) {
         add_menu_page('OSPN - Admin', 'OSPN Admin', 'manage_options', 'ospn-admin-members', 'ospn_admin_members');

         $hook = add_submenu_page('ospn-admin-members', 'OSPN - ' . __('Members'), __('All members'), 'manage_options', 'ospn-admin-members', 'ospn_admin_members');
         add_action('load-' . $hook, 'ospn_admin_add_members_options');

         add_submenu_page('ospn-admin-members', 'OSPN - ' . __('Add New Member'), __('Add'), 'manage_options', 'ospn-admin-member-new', 'ospn_admin_member_new');
	 }
}

add_action('admin_menu', 'ospn_admin_add_menu');

/**
 *
 */
function ospn_admin_add_member() {
    require(dirname(__FILE__) . '/views/member-new.php');
    die();
}

add_action('admin_post_ospn-admin-add-member', 'ospn_admin_add_member');
