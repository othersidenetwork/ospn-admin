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
    public function podcast_edit()
    {
        global $wpdb;

        check_admin_referer("podcast-edit");

        $fetch_rss = array_key_exists("podcast-edit-update-from-rss", $_REQUEST);

        if ($fetch_rss == true) {
            $this->fetch_rss();
        } else {
            $this->update_data();
        }
    }

    private function update_data()
    {
        /** @global $wpdb \wpdb */
        global $wpdb;

        /** @var boolean $active */
        $active = array_key_exists("podcast-active", $_REQUEST) && $_REQUEST["podcast-active"] == "true";

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

    private function fetch_rss() {
        /** @global $wpdb \wpdb */
        global $wpdb;

        /** @var int $blog_id */
        $blog_id = $_REQUEST['blog_id'];

        /** @var string $podcast_feed */
        $podcast_feed = $_REQUEST["podcast-rss-feed"];

        /** @var string $content */
        $content = file_get_contents($podcast_feed);
        if ($content !== false) {
            /** @var \SimpleXMLElement $channel */
            $xml = simplexml_load_string($content);

            /** @var object $channel */
            $channel = $xml->channel;

            /** @var string $podcast_name */
            $podcast_name = $channel->title;

            /** @var string $tagline */
            $tagline = $channel->description;

            /** @var string $logo */
            $logo = $channel->image->url;

            $wpdb->update(
                "{$wpdb->base_prefix}ospn_podcasts",
                array(
                    "podcast_feed" => $podcast_feed,
                    "podcast_name" => $podcast_name,
                    "tagline" => $tagline,
                    "logo" => $logo
                ),
                array(
                    "blog_id" => $blog_id
                ),
                array("%s", "%s", "%s", "%s"),
                array("%d")
            );
        }

        wp_redirect($_REQUEST["_wp_http_referer"]);

        die();
    }
}
