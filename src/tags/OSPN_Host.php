<?php

namespace OSPN\Tags;

use OSPN\OSPN_Base;

class OSPN_Host extends OSPN_Base
{
    /** @var  $ID int */
    private $ID;
    /** @var  $user_nicename string */
    private $user_nicename;
    /** @var  $display_name string */
    private $display_name;
    /** @var  $user_url string */
    private $user_url;
    /** @var  $user_description string */
    private $user_description;

    /** @var  $podcasts array */
    private $podcasts;
    /** @var  $podcast_index int */
    private $podcast_index;

    /** @var  array $contacts */
    private $contacts;
    /** @var  int $contact_index */
    public $contact_index;

    /**
     * OSPN_Host constructor.
     * @param $user_data \WP_User
     */
    function __construct($user_data)
    {
        /** @global $wpdb \wpdb */
        global $wpdb;
        $this->ID = $user_data->ID;
        $this->user_nicename = $user_data->user_nicename;
        $this->display_name = $user_data->display_name;
        $this->user_url = $user_data->user_url;
        $this->user_description = $user_data->user_description;
        $this->podcasts = $wpdb->get_results($wpdb->prepare(<<<SQL
SELECT
  DISTINCT (ph.podcast_id), p.podcast_name, p.logo, p.podcast_slug
FROM
  {$wpdb->base_prefix}ospn_podcast_hosts ph,
  {$wpdb->base_prefix}ospn_podcasts p
WHERE
  ph.host_id = %d
  AND p.blog_id = ph.podcast_id
ORDER BY
  p.podcast_name ASC
SQL
, $this->ID));
        $this->podcast_index = 0;

        $this->contacts = array();
        foreach(wp_get_user_contact_methods() as $meta_key => $value) {
            $meta_value = get_user_meta($this->ID, $meta_key, true);
            if ($meta_value != null && $meta_value != "") {
                $this->contacts[] = new OSPN_Contact($meta_key, $meta_value, $value);
            }
        }
        $this->contact_index = 0;
    }

    public function the_avatar($size = 125, $echo = true) {
        $avatar = get_avatar($this->ID, $size);
        if ($echo) {
            echo $avatar;
        }
        return $avatar;
    }

    public function the_nicename($echo = true) {
        if ($echo) {
            echo $this->user_nicename;
        }
        return $this->user_nicename;
    }

    public function the_name($echo = true) {
        if ($echo) {
            echo $this->display_name;
        }
        return $this->display_name;
    }

    public function the_url($echo = true) {
        if ($echo) {
            echo $this->user_url;
        }
        return $this->user_url;
    }

    public function the_bio($echo = true) {
        if ($echo) {
            echo $this->user_description;
        }
        return $this->user_description;
    }

    public function have_podcasts() {
        return ($this->podcast_index < sizeof($this->podcasts));
    }

    public function the_podcast() {
        $podcast = new OSPN_Podcast($this->podcasts[$this->podcast_index]);
        $this->podcast_index = $this->podcast_index + 1;
        return $podcast;
    }

    public function the_permalink($echo = true) {
        $url = get_home_url(null, "/hosts#{$this->user_nicename}");
        if ($echo) {
            echo $url;
        }
        return $url;
    }

    /**
     * @return bool
     */
    public function have_contacts() {
        return ($this->contact_index < sizeof($this->contacts));
    }

    /**
     * @return OSPN_Contact
     */
    public function the_contact() {
        /** @var OSPN_Contact $contact */
        $contact = $this->contacts[$this->contact_index];
        $this->contact_index = $this->contact_index + 1;
        return $contact;
    }
}