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

		/** @var OSPN_Admin $plugin */
		$plugin = OSPN_Admin::$instance;

		/** @var boolean $active */
		$active = array_key_exists("podcast-active", $_REQUEST) && $_REQUEST["podcast-active"] == "true";

		$wpdb->update(
			"{$wpdb->base_prefix}ospn_podcasts",
			array(
				"podcast_name" => $_REQUEST["podcast-name"],
				"podcast_slug" => $_REQUEST["podcast-slug"],
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
			array("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%d"),
			array("%d")
		);
		$wpdb->delete(
			"{$wpdb->base_prefix}ospn_podcast_hosts",
			array(
				"podcast_id" => $_REQUEST["blog_id"]
			),
			array("%d")
		);
		$i = 0;
		while (array_key_exists( "podcast-host-{$i}", $_REQUEST )) {
			$host_id = $_REQUEST["podcast-host-{$i}"];
			if ($host_id != -1) {
				$wpdb->insert(
					"{$wpdb->base_prefix}ospn_podcast_hosts",
					array(
						"podcast_id" => $_REQUEST["blog_id"],
						"host_id" => $_REQUEST["podcast-host-{$i}"],
						"sequence" => $i
					),
					array("%d", "%d", "%d")
				);
			}
			$i += 1;
		}

		foreach (wp_get_user_contact_methods() as $meta_key => $meta_description) :
			/** @var string $request_key */
			$request_key = "contact_{$meta_key}";

			/** @var string $meta_value */
			$meta_value = $_REQUEST[$request_key];

			/** @var string $sql */
			$sql = $wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}ospn_podcast_meta WHERE podcast_id = %d and meta_key = %s", $_REQUEST["blog_id"], $meta_key);

			/** @var object $meta_entry */
			$meta_entry = $wpdb->get_row($sql);

			if ($meta_entry == '') {
				$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->base_prefix}ospn_podcast_meta (podcast_id, meta_key, meta_value) VALUES(%d, %s, %s)", $_REQUEST["blog_id"], $meta_key, $meta_value));
			} else {
				$wpdb->query($wpdb->prepare("UPDATE {$wpdb->base_prefix}ospn_podcast_meta SET meta_value = %s WHERE pmeta_id = %d", $meta_value, $meta_entry->pmeta_id));
			}
		endforeach;

		/** @var string $sql */
		$sql = $wpdb->prepare("DELETE FROM {$wpdb->base_prefix}ospn_podcast_categories WHERE podcast_id = %d", $_REQUEST["blog_id"]);
		$wpdb->query($sql);

		foreach ($plugin->get_categories() as $category_key => $category_value) {
			/** @var string $request_key */
			$request_key = "category_{$category_key}";

			if (array_key_exists($request_key, $_REQUEST)) {
				/** @var string $form_value */
				$request_value = $_REQUEST[$request_key];

				if ("true" == $request_value) {
					$sql = $wpdb->prepare("INSERT INTO {$wpdb->base_prefix}ospn_podcast_categories(podcast_id, category_slug) VALUES(%d, %s)", $_REQUEST["blog_id"], $category_key);
					$wpdb->query($sql);
				}
			}
		}

		/*
		if ($_REQUEST["origin"] == "admin") {
			wp_redirect(admin_url('network/admin.php') . '?page=ospn-admin-podcasts');
		} else {
		*/
			wp_redirect($_REQUEST["_wp_http_referer"]);
		/*
		}
		*/

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
