<?php
/*
Plugin Name:       Other Side Podcast Network - Admin
Plugin URI:        https://github.com/othersidenetwork/ospn-admin
Description:       Plugin for the administrative needs of the Other Side Podcast Network
Version:           0.1.0
Require WP:        4.4
Require PHP:       5.3.0
Author:            Yannick Mauray
Author URI:        https://github.com/ymauray
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

if (is_admin()) {
	require_once(dirname(__FILE__) . '/admin/ospn-admin.php');
};
