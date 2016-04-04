<?php
/**
 * Created by IntelliJ IDEA.
 * User: yannick
 * Date: 31.03.16
 * Time: 23:10
 */

namespace OSPN\Tags;


use OSPN\OSPN_Admin;
use OSPN\OSPN_Base;

class OSPN_Podcast extends OSPN_Base
{
    /** @var  int $blog_id the id of the blog */
    public $blog_id;
    /** @var string $podcast_name podcast name */
    public $podcast_name;
    /** @var string $podcast_slug */
    public $podcast_slug;
    /** @var string logo (url) */
    public $website;
    /** @var  string $contact */
    public $contact;
    /** @var  string $podcast_feed RSS feed of the podcast */
    public $podcast_feed;
    /** @var  bool $active */
    public $active;
    /** @var string tagline */
    public $tagline;
    /** @var  string $logo URL */
    public $logo;
    /** @var  string $description */
    public $description;

    /** @var array|null $hosts */
    public $hosts;
    /** @var int $host_index */
    public $host_index;

    /** @var  array $contacts */
    public $contacts;
    /** @var  int $contact_index */
    public $contact_index;

    function __construct($info)
    {
        /** @global $wpdb \wpdb */
        global $wpdb;

        /** @var array $properties */
        $properties = array("blog_id", "podcast_name", "podcast_slug", "website", "contact", "podcast_feed", "active", "tagline", "logo", "description");

        foreach ($properties as $property) {
            $this->$property = $info->$property;
        }

        /** @var string $sql */
        $sql = $wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}ospn_podcast_hosts WHERE podcast_id = %d ORDER BY sequence ASC", $this->blog_id);

        /** @var array $results */
        $results = $wpdb->get_results($sql);

        $this->hosts = $results;
        $this->host_index = 0;
    }

    public function the_name($echo = true) {
        if ($echo) {
            echo $this->podcast_name;
        }
        return $this->podcast_name;
    }

    public function the_logo($echo = true) {
        if ($echo) {
            echo $this->logo;
        }
        return $this->logo;
    }

    /**
     * @param bool $echo
     * @return string
     */
    public function the_url($echo = true)
    {
        if ($echo) {
            echo $this->website;
        }
        return $this->website;
    }

    /**
     * @return bool
     */
    public function have_socials()
    {
        return false;
    }

    public function the_social()
    {

    }

    /**
     * @param bool $echo
     * @return string
     */
    public function the_description($echo = true)
    {
        if ($echo) {
            echo $this->description;
        }
        return $this->description;
    }

    /**
     * @param bool $echo
     * @return string
     */
    public function the_hosts_title($echo = true) {
        $title = sizeof($this->hosts) == 1 ? __("The Host") : __("The Hosts");
        if ($echo) {
            echo $title;
        }
        return $title;
    }

    /**
     * @return bool
     */
    public function have_hosts() {
        /** @var bool $result */
        $result = $this->host_index < sizeof($this->hosts);

        return $result;
    }

    /**
     * @return OSPN_Host
     */
    public function the_host() {
        /** @var OSPN_Host $host */
        $host = new OSPN_Host(get_userdata($this->hosts[$this->host_index]->host_id));

        $this->host_index = $this->host_index + 1;

        return $host;
    }

    /**
     * @param bool $echo
     * @return string
     */
    public function the_permalink($echo = true)
    {
        /** @var string $permalink */
        $permalink = get_home_url(null, "/" . OSPN_Admin::PODCASTS_ENDPOINT . "/{$this->podcast_slug}");
        if ($echo) {
            echo $permalink;
        }
        return $permalink;
    }

    /**
     * @param bool $echo
     * @return string
     */
    public function the_tagline($echo = true)
    {
        if ($echo) {
            echo $this->tagline;
        }
        return $this->tagline;
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