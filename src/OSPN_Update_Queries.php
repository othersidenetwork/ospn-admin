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
  PRIMARY KEY  podcasts_pk (blog_id)
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
        /** @var string $sql */
        $sql = <<<TAG
CREATE TABLE {$wpdb->base_prefix}ospn_podcast_hosts (
  podcast_id bigint(20) NOT NULL,
  host_id bigint(20) NOT NULL,
  sequence tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY  podcast_hosts_pk (podcast_id, host_id)
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
  PRIMARY KEY  socials_pk (ID)
) $charset_collate;
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
  PRIMARY KEY  podcast_socials_pk (ID)
) $charset_collate;
TAG;
        return $sql;
    }

    /**
     *
     */
    public static function update_data()
    {
        OSPN_Update_Queries::update_type_socials();
    }

    /**
     *
     */
    private function update_type_socials() {
        global $wpdb;

        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->base_prefix}ospn_socials");
        if ($count == 0) {
            $wpdb->insert(
                "{$wpdb->base_prefix}ospn_socials",
                array(
                    "name" => "Twitter username",
                    "placeholder" => "@...",
                    "pattern" => "^@.+$"
                ),
                array("%s", "%s", "%s")
            );
            $wpdb->insert(
                "{$wpdb->base_prefix}ospn_socials",
                array(
                    "name" => "Facebook URL",
                    "placeholder" => "https://facebook.com/...",
                    "pattern" => '^https?://(www\\.)?facebook\\.com/.+$'
                ),
                array("%s", "%s", "%s")
            );
            $wpdb->insert(
                "{$wpdb->base_prefix}ospn_socials",
                array(
                    "name" => "Google+ URL",
                    "placeholder" => "https://plus.google.com/...",
                    "pattern" => '^https?://plus\\.google\\.com/.+$'
                ),
                array("%s", "%s", "%s")
            );
            $wpdb->insert(
                "{$wpdb->base_prefix}ospn_socials",
                array(
                    "name" => "GNU Social URL",
                    "placeholder" => "http://...",
                    "pattern" => '^https?://.+\\.[^\\.]+/.+$'
                ),
                array("%s", "%s", "%s")
            );
            $wpdb->insert(
                "{$wpdb->base_prefix}ospn_socials",
                array(
                    "name" => "diaspora* URL",
                    "placeholder" => "http://...",
                    "pattern" => '^https?://.+\\.[^\\.]+/.+$'
                ),
                array("%s", "%s", "%s")
            );
        }
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