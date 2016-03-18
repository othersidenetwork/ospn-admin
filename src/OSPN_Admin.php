<?php
/**
 * Created by IntelliJ IDEA.
 * User: yannick
 * Date: 18.03.16
 * Time: 07:46
 */

namespace OSPN;


use OSPN\OSPN_MenuActions;
use OSPN\OSPN_UpdateQueries;

/**
 * Class OSPN_Admin
 * @package OSPN
 */
class OSPN_Admin
{
    /**
     * @var \OSPN\OSPN_MenuActions
     */
    private $menu_actions;

    /**
     * @var string
     */
    private $db_version = '0.1.0';

    /**
     * OSPN_Admin constructor.
     */
    function __construct()
    {
        $this->menu_actions = new OSPN_MenuActions();
    }

    /**
     *
     */
    public function activate() {
        // Not implemented yet.
    }

    /**
     *
     */
    public function deactivate() {
        // Not implemented
    }

    /**
     *
     */
    public function loaded() {
        $this->update_db();
    }

    /**
     *
     */
    public function install_menu() {
        if (current_user_can('manage_options')) {
            add_menu_page('OSPN - Admin', 'OSPN Admin', 'manage_options', 'ospn-admin-members', array($this->menu_actions, 'members'));

            $hook = add_submenu_page('ospn-admin-members', 'OSPN - ' . __('Members'), __('All members'), 'manage_options', 'ospn-admin-members', 'ospn_admin_members');
            add_action('load-' . $hook, 'ospn_admin_add_members_options');

            add_submenu_page('ospn-admin-members', 'OSPN - ' . __('Add New Member'), __('Add'), 'manage_options', 'ospn-admin-member-new', 'ospn_admin_member_new');
        }
    }

    // Private methods

    /**
     * @param $message string
     */
    private function log($message) {
        error_log($message, 4);
    }

    /**
     *
     */
    private function update_db()
    {
        $installed_version = get_option("ospn_admin_db_version");
        if ($installed_version != $this->db_version) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta(OSPN_UpdateQueries::members());
            update_option("ospn_admin_db_version", $this->db_version);
            add_action('admin_notices', function() {
                $message = __('Your database has been updated.', 'ospn-admin');
                echo sprintf('<div class="notice notice-success is-dismissible"><p>%s</p></div>', $message);
            });
        }
    }
}