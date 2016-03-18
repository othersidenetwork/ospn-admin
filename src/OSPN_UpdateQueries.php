<?php
/**
 * Created by IntelliJ IDEA.
 * User: yannick
 * Date: 18.03.16
 * Time: 08:20
 */

namespace OSPN;


class OSPN_UpdateQueries
{
    public static function members() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = <<<TAG
CREATE TABLE {$wpdb->prefix}ospn_members (
  ID bigint(20) NOT NULL,
  `name` tinytext NOT NULL,
  `host` tinytext NOT NULL,
  website text NOT NULL,
  active tinyint(1) NOT NULL DEFAULT '1',
  contact tinytext NOT NULL,
  podcast_feed text NOT NULL,
  twitter_handle tinytext,
  facebook_url text,
  google_plus_url text,
  PRIMARY KEY  ID (ID)
) $charset_collate;
TAG;
        return $sql;
    }
}