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
     * @var OSPN_PostActions $post_actions
     */
    private $post_actions;

    /**
     * @var string
     */
    private $db_version = '0.1.0a';

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
        add_action('admin_post_ospn-admin-podcast-edit', array($this->post_actions, 'podcast_edit'));
    }

    /**
     *
     */
    public function register_actions() {
        add_action('plugins_loaded', array($this, 'loaded'));
        add_action('admin_menu', array($this, 'install_menu'));
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
            dbDelta(OSPN_UpdateQueries::poscasts());
            dbDelta(OSPN_UpdateQueries::socials());
            dbDelta(OSPN_UpdateQueries::podcast_socials());
            OSPN_UpdateQueries::update_data();
            update_option("ospn_admin_db_version", $this->db_version);
            add_action('admin_notices', function() {
                $message = __('Your database has been updated.', 'ospn-admin');
                echo sprintf('<div class="notice notice-success is-dismissible"><p>%s</p></div>', $message);
            });
        }

        OSPN_UpdateQueries::update_blog_names();
    }

    /**
     *
     */
    public function install_menu() {
        $plugin = $this;
        if (current_user_can('manage_options')) {
            add_menu_page('OSPN - Admin', 'OSPN Admin', 'manage_options', 'ospn-admin-podcasts');

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