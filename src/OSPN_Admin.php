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
class OSPN_Admin extends OSPN_Base
{
    /**
     * @var \OSPN\OSPN_MenuActions
     */
    private $menu_actions;

    /**
     * @var OSPN_PostActions
     */
    private $post_actions;

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
        $this->post_actions = new OSPN_PostActions();
    }

    /**
     *
     */
    public function register_post_actions() {
        add_action('admin_post_ospn-member-new', [$this->post_actions, 'member_new']);
    }

    /**
     *
     */
    public function register_actions() {
        add_action('plugins_loaded', [$this, 'loaded']);
        add_action('admin_menu', [$this, 'install_menu']);
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

    /**
     *
     */
    public function install_menu() {
        $plugin = $this;
        if (current_user_can('manage_options')) {
            add_menu_page('OSPN - Admin', 'OSPN Admin', 'manage_options', 'ospn-admin-members');

            $hook = add_submenu_page('ospn-admin-members', 'OSPN - ' . __('Members'), __('All members'), 'manage_options', 'ospn-admin-members', function() use (&$plugin) {
                $plugin->read_view('members.php');
            });
            add_action('load-' . $hook, [$this->menu_actions, 'members']);

            $hook = add_submenu_page('ospn-admin-members', 'OSPN - ' . __('Add New Member'), __('Add'), 'manage_options', 'ospn-admin-member-new', function() use (&$plugin) {
                $plugin->read_view('member_new.php');
            });
            add_action('load-' . $hook, [$this->menu_actions, 'member_new']);
        }
    }

    // Private methods

    /**
     * @param $view string
     */
    private function read_view($view) {
        ob_start();
        include (dirname(dirname(__FILE__)) . '/views/' . $view);
        echo ob_get_clean();
    }

}