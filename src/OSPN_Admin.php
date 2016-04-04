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
    const PODCASTS_ENDPOINT = "shows";
    const HOSTS_ENDPOINT = "hosts";

    /** @var \OSPN\OSPN_Menu_Actions $menu_actions */
    private $menu_actions;

    /** @var OSPN_Post_Actions $post_actions */
    private $post_actions;

    /** @var string $db_version */
    private $db_version = '0.4.0';

    /** @var OSPN_Admin $instance */
    public static $instance;

    /**
     * OSPN_Admin constructor.
     */
    function __construct()
    {
        $this->menu_actions = new OSPN_Menu_Actions();
        $this->post_actions = new OSPN_Post_Actions();
        OSPN_Admin::$instance = $this;
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
        add_action('init', array($this, 'init'));
        add_action('plugins_loaded', array($this, 'loaded'));
        add_action('network_admin_menu', array($this, 'install_menu'));
        add_action('admin_menu', array($this, 'install_simple_menu'));
    }

    /**
     *
     */
    public function activate() {
        flush_rewrite_rules();
    }

    /**
     *
     */
    public function deactivate() {
        flush_rewrite_rules();
    }

    /**
     *
     */
    public function init() {
        /** @var OSPN_Admin $admin */
        $admin = $this;
        add_filter('user_contactmethods', function($contact_methods) {
            $contact_methods['twitter'] = 'Twitter';
            $contact_methods['facebook'] = 'Facebook';
            $contact_methods['googleplus'] = 'Google +';
            $contact_methods['diaspora'] = 'Diaspora*';
            $contact_methods['gnusocial'] = 'GNU Social';
            return $contact_methods;
        });
        add_rewrite_endpoint(OSPN_Admin::PODCASTS_ENDPOINT, EP_ROOT);
        add_rewrite_endpoint(OSPN_Admin::HOSTS_ENDPOINT, EP_ROOT);
        add_filter('query_vars', function($vars) {
            if (!array_key_exists(OSPN_Admin::PODCASTS_ENDPOINT, $vars)) {
                $vars[] = OSPN_Admin::PODCASTS_ENDPOINT;
            }
            if (!array_key_exists(OSPN_Admin::HOSTS_ENDPOINT, $vars)) {
                $vars[] = OSPN_Admin::HOSTS_ENDPOINT;
            };
            return $vars;
        });
        add_filter('request', function($vars) use($admin) {
            if ($vars != null && is_array($vars) && array_key_exists(OSPN_Admin::PODCASTS_ENDPOINT, $vars) && "" == $vars[OSPN_Admin::PODCASTS_ENDPOINT]) {
                $vars[OSPN_Admin::PODCASTS_ENDPOINT] = "all";
            }
            if ($vars != null && is_array($vars) && array_key_exists(OSPN_Admin::HOSTS_ENDPOINT, $vars) && "" == $vars[OSPN_Admin::HOSTS_ENDPOINT]) {
                $vars[OSPN_Admin::HOSTS_ENDPOINT] = "all";
            }
            if ($vars[OSPN_Admin::PODCASTS_ENDPOINT] == "json") {
                $admin->json_podcasts();
            }
            return $vars;
        });
        add_filter('template_include', function($template) {
            /** @global $ospn OSPN_Plugin */
            global $ospn;
            /** @var string $podcasts */
            $podcasts = get_query_var(OSPN_Admin::PODCASTS_ENDPOINT);
            if ($podcasts != null && $podcasts != "") {
                $ospn = new OSPN_Plugin($podcasts);
                /** @var string $podcast_template */
                $podcast_template = locate_template(array("podcast-{$podcasts}.php", "podcast.php"));
                return $podcast_template;
            }
            $hosts = get_query_var(OSPN_Admin::HOSTS_ENDPOINT);
            if ($hosts != null && $hosts != "") {
                $ospn = new OSPN_Plugin(null, $hosts);
                $hosts_template = locate_template(array("hosts.php"));
                return $hosts_template;
            }
            return $template;
        });
    }

    /**
     *
     */
    public function loaded() {
        /** @var string $installed_version */
        $installed_version = get_option("ospn_admin_db_version");
        if (is_network_admin() && $installed_version != $this->db_version) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta(OSPN_Update_Queries::podcasts());
            dbDelta(OSPN_Update_Queries::podcast_hosts());
            dbDelta(OSPN_Update_Queries::podcast_meta());
            dbDelta(OSPN_Update_Queries::podcast_categories());
            update_option("ospn_admin_db_version", $this->db_version);
        }

        OSPN_Update_Queries::update_blog_names();
        OSPN_Update_Queries::update_blog_slugs();
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

    /**
     * @return array
     */
    public function get_categories() {
        return array(
            "discussion" => __("Discussion"),
            "music" => __("Music"),
            "technology" => __("Technology")
        );
    }

    /**
     *
     */
    public function json_podcasts() {
        /** @global $wpdb \wpdb */
        global $wpdb;

        /** @var array $podcasts */
        $podcasts = array();

        /** @var array $categories */
        $categories = array();

        /** @var string $sql */
        $sql = $wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}ospn_podcasts WHERE active = %d", true);

        /** @var array $results */
        $results = $wpdb->get_results($sql);

        /** @var object $result */
        foreach($results as $result) {
            $podcasts[$result->podcast_slug] = array(
                "name" => $result->podcast_name,
                "url" => $result->website
            );

            /** @var string $subsql */
            $subsql = $wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}ospn_podcast_categories WHERE podcast_id = %d", $result->blog_id);

            /** @var array $subresults */
            $subresults = $wpdb->get_results($subsql);

            /** @var object $subresult */
            foreach($subresults as $subresult) {
                if (!array_key_exists($subresult->category_slug, $categories)) {
                    $categories[$subresult->category_slug] = array();
                }
                if (!array_key_exists($result->podcast_slug, $categories[$subresult->category_slug])) {
                    $categories[$subresult->category_slug][$result->podcast_slug] = $result->podcast_name;
                }
            }
        }

        $cat = array();
        $c = $this->get_categories();

        foreach($categories as $category_slug => $podcasts_of_category) {
            /** @var object $a */
            /** @var object $b */
            uasort($podcasts_of_category, function($podcast_name_a, $podcast_name_b) {
                return strcmp($podcast_name_a, $podcast_name_b);
            });

            $cat[] = array(
                "name" => $c[$category_slug],
                "podcasts" => array_keys($podcasts_of_category)
            );
        }

        usort($cat, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        /** @var array $response */
        $response = array(
            "podcasts" => $podcasts,
            "categories" => $cat
        );

        wp_send_json($response);
    }
}