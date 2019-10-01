<?php

namespace OSPN;


class OSPN_Update_Queries extends OSPN_Base
{
    /**
     * @return string
     */
    public static function podcasts() {
        global $wpdb;
        /** @var string $charset_collate */
        $charset_collate = $wpdb->get_charset_collate();
        /** @var string $sql */
        $sql = <<<TAG
CREATE TABLE {$wpdb->base_prefix}ospn_podcasts (
  blog_id bigint(20) NOT NULL,
  podcast_name tinytext NOT NULL,
  podcast_slug tinytext,
  tagline text NOT NULL,
  logo tinytext NOT NULL,
  description mediumtext NOT NULL,
  website tinytext NOT NULL,
  contact tinytext NOT NULL,
  podcast_feed tinytext NOT NULL,
  active tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY  (blog_id)
) $charset_collate;
TAG;
        return $sql;
    }

    /**
     * @return string
     */
    public static function podcast_hosts() {
        global $wpdb;
        /** @var string $charset_collate */
        $charset_collate = $wpdb->get_charset_collate();

        /** @var string $table */
        $table = $wpdb->base_prefix . "ospn_podcast_hosts";
        if ($wpdb->get_var("SHOW TABLES LIKE {$table}" == "{$table}")) {
            $wpdb->query("ALTER TABLE {$table} DROP PRIMARY KEY");
        }        

        /** @var string $sql */
        $sql = <<<TAG
CREATE TABLE {$table} (
  podcast_id bigint(20) NOT NULL,
  host_id bigint(20) NOT NULL,
  sequence tinyint(1) NOT NULL DEFAULT 0,
  role bigint(20) NOT NULL DEFAULT 1,
  PRIMARY KEY  (podcast_id,host_id,role)
) $charset_collate;
TAG;
        return $sql;
    }

    public static function socials() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = <<<TAG
CREATE TABLE {$wpdb->base_prefix}ospn_socials (
  ID bigint(20) NOT NULL AUTO_INCREMENT,
  name tinytext NOT NULL,
  placeholder tinytext NOT NULL,
  pattern text NOT NULL,
  PRIMARY KEY  (ID)
) {$charset_collate};
TAG;
        return $sql;
    }

    public static function podcast_socials() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = <<<TAG
CREATE TABLE {$wpdb->base_prefix}ospn_podcast_socials (
  ID bigint(20) NOT NULL AUTO_INCREMENT,
  socials_id bigint(20),
  value longtext,
  PRIMARY KEY  (ID)
) {$charset_collate};
TAG;
        return $sql;
    }

    public static function podcast_meta() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = <<<TAG
CREATE TABLE {$wpdb->base_prefix}ospn_podcast_meta (
  pmeta_id bigint(20) NOT NULL AUTO_INCREMENT,
  podcast_id bigint(20),
  meta_key varchar(255),
  meta_value longtext,
  PRIMARY KEY  (pmeta_id)
) {$charset_collate};
TAG;
        return $sql;
    }

    public static function podcast_categories() {
        /** @global $wpdb \wpdb */
        global $wpdb;

        /** @var string $charset_collate */
        $charset_collate = $wpdb->get_charset_collate();

        /** @var string $sql */
        $sql = <<<TAG
CREATE TABLE {$wpdb->base_prefix}ospn_podcast_categories (
  podcast_id bigint(20) NOT NULL,
  category_slug varchar(64) NOT NULL,
  UNIQUE KEY uk_cat (podcast_id,category_slug)
) {$charset_collate};
TAG;
        return $sql;
    }

    public static function podcast_roles() {
        /** @global $wpdb \wpdb */
        global $wpdb;

        /** @var string $charset_collate */
        $charset_collate = $wpdb->get_charset_collate();

        /** @var array $queries */
        $queries = [];

        $queries[] = <<<TAG
CREATE TABLE {$wpdb->base_prefix}ospn_podcast_roles (
  role_id bigint(20) NOT NULL,
  role_name varchar(32) NOT NULL,
  PRIMARY KEY  (role_id)
) {$charset_collate};
TAG;

        $queries[] = <<<TAG
INSERT INTO {$wpdb->base_prefix}ospn_podcast_roles(role_id, role_name) VALUES(1, "Host");
TAG;

$queries[] = <<<TAG
INSERT INTO {$wpdb->base_prefix}ospn_podcast_roles(role_id, role_name) VALUES(2, "Producer");
TAG;

$queries[] = <<<TAG
INSERT INTO {$wpdb->base_prefix}ospn_podcast_roles(role_id, role_name) VALUES(3, "Contributor");
TAG;

        return $queries;
    }

    public static function update_blog_names() {
        /** @global $wpdb \wpdb */
        global $wpdb;
        /** @var array $results */
        $results = $wpdb->get_results(<<<TAG
SELECT
	b.blog_id,
	p.blog_id p_blog_id,
	p.podcast_name
FROM
	{$wpdb->blogs} b
	LEFT JOIN {$wpdb->base_prefix}ospn_podcasts p ON b.blog_id = p.blog_id
HAVING
	p_blog_id IS null
	AND b.blog_id > 1
TAG
);
        /** @var object $row */
        foreach($results as $row) {
            /** @var string $sql */
            $sql = <<<TAG
SELECT
	o.option_value
from
	{$wpdb->base_prefix}{$row->blog_id}_options o
WHERE
	o.option_name = 'blogname';
TAG;
            /** @var string $blog_name */
            $blog_name = $wpdb->get_var($sql);
            $wpdb->insert(
                "{$wpdb->base_prefix}ospn_podcasts",
                array(
                    "blog_id" => $row->blog_id,
                    "podcast_name" => $blog_name
                ),
                array("%d", "%s")
            );
        }
    }

    public static function update_blog_slugs() {
        /** @global $wpdb \wpdb */
        global $wpdb;
        /** @var array $results */
        $results = $wpdb->get_results(<<<TAG
SELECT
	b.blog_id,
	p.blog_id p_blog_id,
    p.podcast_name,
    p.podcast_slug
FROM
	{$wpdb->blogs} b
	LEFT JOIN {$wpdb->base_prefix}ospn_podcasts p ON b.blog_id = p.blog_id
HAVING
	p.podcast_slug IS null
	AND b.blog_id > 1
TAG
        );
        /** @var object $row */
        foreach($results as $row) {
            /** @var string $podcast_name */
            $podcast_name = $row->podcast_name;
            /** @var string $podcast_slug */
            $podcast_slug = sanitize_title_for_query($podcast_name);
            $wpdb->update(
                "{$wpdb->base_prefix}ospn_podcasts",
                array(
                    "podcast_slug" => $podcast_slug
                ),
                array(
                    "blog_id" => $row->blog_id
                ),
                array("%s"),
                array("%d")
            );
        }
    }
}