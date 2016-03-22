<?php

namespace OSPN;

class OSPN_Post_Actions extends OSPN_Base
{

    /**
     * OSPN_PostActions constructor.
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public function podcast_edit() {
        global $wpdb;

        /** @var boolean $active */
        $active = defined($_REQUEST["podcast-active"]);

        check_admin_referer("podcast-edit");

        $wpdb->update(
            "{$wpdb->prefix}ospn_podcasts",
            array(
                "podcast_name" => $_REQUEST["podcast-name"],
                "website" => $_REQUEST["podcast-website"],
                "contact" => $_REQUEST["podcast-email"],
                "podcast_feed" => $_REQUEST["podcast-rss-feed"],
                "active" => $active
            ),
            array(
                "blog_id" => $_REQUEST['blog_id']
            ),
            array("%s", "%s", "%s", "%s", "%d"),
            array("%d")
        );
        $wpdb->delete(
            "{$wpdb->prefix}ospn_podcast_hosts",
            array(
                "podcast_id" => $_REQUEST["blog_id"]
            ),
            array("%d")
        );
        $wpdb->insert(
            "{$wpdb->prefix}ospn_podcast_hosts",
            array(
                "podcast_id" => $_REQUEST["blog_id"],
                "host_id" => $_REQUEST["podcast-host"]
            ),
            array("%d", "%d")
        );
        if ($_REQUEST["podcast-host2"] != -1) {
            $wpdb->insert(
                "{$wpdb->prefix}ospn_podcast_hosts",
                array(
                    "podcast_id" => $_REQUEST["blog_id"],
                    "host_id" => $_REQUEST["podcast-host2"]
                ),
                array("%d", "%d")
            );
        }
        wp_redirect(admin_url('admin.php') . '?page=ospn-admin-podcasts');
        die();
    }
}
