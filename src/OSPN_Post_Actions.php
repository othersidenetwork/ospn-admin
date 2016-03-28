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
            "{$wpdb->base_prefix}ospn_podcasts",
            array(
                "podcast_name" => $_REQUEST["podcast-name"],
                "tagline" => $_REQUEST["podcast-tagline"],
                "description" => $_REQUEST["podcast-description"],
                "logo" => $_REQUEST["podcast-logo"],
                "website" => $_REQUEST["podcast-website"],
                "contact" => $_REQUEST["podcast-email"],
                "podcast_feed" => $_REQUEST["podcast-rss-feed"],
                "active" => $active
            ),
            array(
                "blog_id" => $_REQUEST['blog_id']
            ),
            array("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%d"),
            array("%d")
        );
        $wpdb->delete(
            "{$wpdb->base_prefix}ospn_podcast_hosts",
            array(
                "podcast_id" => $_REQUEST["blog_id"]
            ),
            array("%d")
        );
        $wpdb->insert(
            "{$wpdb->base_prefix}ospn_podcast_hosts",
            array(
                "podcast_id" => $_REQUEST["blog_id"],
                "host_id" => $_REQUEST["podcast-host"],
                "sequence" => 0
            ),
            array("%d", "%d", "%d")
        );
        if ($_REQUEST["podcast-host2"] != -1) {
            $wpdb->insert(
                "{$wpdb->base_prefix}ospn_podcast_hosts",
                array(
                    "podcast_id" => $_REQUEST["blog_id"],
                    "host_id" => $_REQUEST["podcast-host2"],
                    "sequence" => 1
                ),
                array("%d", "%d", "%d")
            );
        }
        if ($_REQUEST["origin"] == "admin") {
            wp_redirect(admin_url('network/admin.php') . '?page=ospn-admin-podcasts');
        } else {
            wp_redirect($_REQUEST["_wp_http_referer"]);
        }

        die();
    }
}
