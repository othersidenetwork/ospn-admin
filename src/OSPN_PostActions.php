<?php
/**
 * Created by IntelliJ IDEA.
 * User: yannick
 * Date: 19.03.16
 * Time: 13:07
 */

namespace OSPN;


class OSPN_PostActions extends OSPN_Base
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
                "blog_name" => $_REQUEST["podcast-name"],
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
        wp_redirect(admin_url('admin.php') . '?page=ospn-admin-podcasts');
        die();
    }
}
