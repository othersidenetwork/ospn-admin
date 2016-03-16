<?php
function ospn_admin_activate() {
	//flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'ospn_admin_activate');

function ospn_admin_deactivate() {
	//flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'ospn_admin_deactivate');

function ospn_admin_members() {
	ob_start();
	require(dirname(__FILE__) . '/views/members.php');
	$output = ob_get_clean();
	echo $output;
}

function ospn_admin_add_menu() {
	 if (current_user_can('manage_options')) {
	 	add_menu_page('OSPN - Admin', 'OSPN Admin', 'manage_options', 'ospn-admin-members', 'ospn_admin_members');
	 	add_submenu_page('ospn-admin-members', 'OSPN - Members', 'Member', 'manage_options', 'ospn-admin-members', 'ospn_admin_members');
	 }
}

add_action('admin_menu', 'ospn_admin_add_menu');
