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
    public function member_new() {
        global $wpdb;

        $_wp_http_referer = $_REQUEST['_wp_http_referer'];
        $this->log("request : " . json_encode($_REQUEST));
        $this->log("referer: " . $_wp_http_referer);

        $wpdb->insert($wpdb->prefix . 'ospn_podcasts', [
            'name' => $_REQUEST['podcast-name'],
            'host' => $_REQUEST['podcast-host'],
            'website' => $_REQUEST['podcast-website'],
            'active' => ($_REQUEST['podcast-active'] == 'true'),
            'contact' => $_REQUEST['podcast-email'],
            'podcast_feed' => $_REQUEST['podcast-rss-feed'],
            'twitter_handle' => $_REQUEST['podcast-twitter'],
            'facebook_url' => $_REQUEST['podcast-facebook-url'],
            'google_plus_url' => $_REQUEST['podcast-google-plus-url']
        ], ['%s', '%d', '%s','%d', '%s', '%s', '%s', '%s', '%s']);

        wp_redirect(admin_url('admin.php') . '?page=ospn-admin-members');
    }
}