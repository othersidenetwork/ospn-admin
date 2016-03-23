<?php
/*
Plugin Name:       Other Side Podcast Network - Admin
Plugin URI:        https://github.com/othersidenetwork/ospn-admin
Description:       Plugin for the administrative needs of the Other Side Podcast Network
Network:           true
Version:           0.1.0
Require WP:        4.4
Require PHP:       5.3.0
Author:            Other Side Podcast Network
Author URI:        https://github.com/othersidenetwork
License:           GNU General Public License v3
License URI:       http://www.gnu.org/licenses/gpl-3.0.html
Domain Path:       /languages
Text Domain:       ospn-admin
GitHub Plugin URI: https://github.com/othersidenetwork/ospn-admin
GitHub Branch:     master

Copyright (C) 2016  Yannick Mauray, Dave Lee

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

use OSPN\OSPN_Admin;

if (!defined('WPINC')) {
	die;
}

if (is_network_admin() || (array_key_exists("_wp_http_referer", $_REQUEST) && strncmp($_REQUEST["_wp_http_referer"], "/wp-admin/network/admin.php?page=ospn-", 38) == 0)) {
    require_once('vendor/autoload.php');
    $plugin = new OSPN_Admin();

    register_activation_hook(__FILE__, array($plugin, 'activate'));
    register_deactivation_hook(__FILE__, array($plugin, 'deactivate'));

    wp_enqueue_script("ospn-validation", plugin_dir_url(__FILE__) . "js/validation.js", array("jquery"));
    wp_enqueue_style("ospn-validation", plugin_dir_url(__FILE__) . "css/style.css");

    $plugin->register_post_actions();
    $plugin->register_actions();

};
