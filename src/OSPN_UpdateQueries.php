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
  ID bigint(20) NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `host` bigint(20) NOT NULL,
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