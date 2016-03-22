<?php
/**
 * Created by IntelliJ IDEA.
 * User: yannick
 * Date: 18.03.16
 * Time: 08:20
 */

namespace OSPN;


class OSPN_UpdateQueries extends OSPN_Base
{
    public static function members() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = <<<TAG
CREATE TABLE {$wpdb->prefix}ospn_podcasts (
  blog_id bigint(20) NOT NULL,
  host_id bigint(20) NOT NULL,
  website text NOT NULL,
  contact tinytext NOT NULL,
  podcast_feed text NOT NULL,
  twitter_handle tinytext,
  facebook_url text,
  google_plus_url text,
  active tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY  blog_id (blog_id)
) $charset_collate;
TAG;
        return $sql;
    }
}