<?php

namespace OSPN;


use OSPN\OSPN_Menu_Actions;
use OSPN\OSPN_Update_Queries;

/**
 * Class OSPN_Admin
 * @package OSPN
 */
class OSPN_Admin extends OSPN_Base
{
    /** @var \OSPN\OSPN_Menu_Actions $menu_actions */
    private $menu_actions;

    /** @var OSPN_Post_Actions $post_actions */
    private $post_actions;

    /** @var string $db_version */
    private $db_version = '0.2.0';

    /**
     * OSPN_Admin constructor.
     */
    function __construct()
    {
        $this->menu_actions = new OSPN_Menu_Actions();
        $this->post_actions = new OSPN_Post_Actions();
    }

    /**
     *
     */
    public function register_post_actions() {
        add_action('admin_post_ospn-admin-podcast-edit', array($this->post_actions, 'podcast_edit'));
    }

    /**
     *
     */
    public function register_actions() {
        add_action('plugins_loaded', array($this, 'loaded'));
        add_action('network_admin_menu', array($this, 'install_menu'));
        add_action('admin_menu', array($this, 'install_simple_menu'));
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
        /** @var string $installed_version */
        $installed_version = get_option("ospn_admin_db_version");
        if ($installed_version != $this->db_version) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta(OSPN_Update_Queries::podcasts());
            dbDelta(OSPN_Update_Queries::podcast_hosts());
            dbDelta(OSPN_Update_Queries::socials());
            dbDelta(OSPN_Update_Queries::podcast_socials());
            OSPN_Update_Queries::update_data();
            update_option("ospn_admin_db_version", $this->db_version);
            add_action('admin_notices', function() {
                /** @var string $message */
                $message = __('Your database has been updated.', 'ospn-admin');
                echo sprintf('<div class="notice notice-success is-dismissible"><p>%s</p></div>', $message);
            });
        }

        OSPN_Update_Queries::update_blog_names();
    }

    /**
     *
     */
    public function install_menu() {
        /** @var OSPN_Admin $plugin */
        $plugin = $this;
        if (current_user_can('manage_sites')) {
            add_menu_page('OSPN - Admin', 'OSPN Admin', 'manage_options', 'ospn-admin-podcasts');

            /** @var string $hook */
            $hook = add_submenu_page('ospn-admin-podcasts', 'OSPN - ' . __('Podcasts'), __('All podcasts'), 'manage_options', 'ospn-admin-podcasts', function() use (&$plugin) {
                $plugin->read_view('podcasts.php');
            });
            add_action('load-' . $hook, array($this->menu_actions, 'podcasts'));

            $hook = add_submenu_page(null, 'OSPN - ' . __('Edit Podcast'), __('Edit podcast'), 'manage_options', 'ospn-admin-podcast-edit', function() use (&$plugin) {
                $plugin->read_view('podcast.php');
            });
            add_action('load-' . $hook, array($this->menu_actions, 'podcast_edit'));
        }
    }

    public function install_simple_menu() {
        if (get_current_blog_id() != 1) {
            /** @var OSPN_Admin $plugin */
            $plugin = $this;
            add_menu_page('OSPN', 'OSPN', 'manage_options', 'ospn-profile');

            /** @var string $hook */
            $hook = add_submenu_page('ospn-profile', 'OSPN - ' . __('Profile'), __('Profile'), 'manage_options', 'ospn-profile', function () use (&$plugin) {
                $plugin->read_view('podcast.php');
            });
            add_action('load-' . $hook, array($this->menu_actions, 'profile'));
        } else {
            $this->install_menu();
        }
    }

    /**
     * @param $view string
     */
    public function read_view($view) {
        ob_start();
        include (dirname(dirname(__FILE__)) . '/views/' . $view);
        echo ob_get_clean();
    }
}