<?php
/**
 * Created by IntelliJ IDEA.
 * User: yannick
 * Date: 18.03.16
 * Time: 08:27
 */

namespace OSPN;

/**
 * Class OSPN_MenuActions
 * @package OSPN
 */
class OSPN_MenuActions extends OSPN_Base
{
    public function members() {
        global $wpdb;

        $blog_ids = $wpdb->get_results("SELECT blog_id FROM wp_blogs WHERE blog_id > 1");
        
        SELECT * from wp_blogs LEFT JOIN wp_ospn_podcasts on wp_blogs.blog_id = wp_ospn_podcasts.blog_id;
    }

    public function member_new() {
        global $wpdb, $allusers;
        $allusers = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY display_name ASC");
    }

}