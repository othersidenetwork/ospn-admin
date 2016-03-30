<?php
/**
 * Created by IntelliJ IDEA.
 * User: yannick
 * Date: 28.03.16
 * Time: 22:33
 */

namespace OSPN;


class OSPN_Plugin extends OSPN_Base
{
    /** @var array[int] $ids */
    private $ids;

    /** @var int $index */
    private $index;
    /** @var  int $host_index */
    private $host_index;

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

    /** @var  array $hosts */
    public $hosts;
    /** @var  $host \WP_User */
    public $host;

    /**
     * OSPN_Plugin constructor.
     * @param $podcast string
     */
    function __construct($podcast, $hosts = null)
    {
        /** @global $wpdb \wpdb */
        global $wpdb;

        /** @var string|null $sql */
        $sql = null;
        if ($podcast != null) {
            $sql = "SELECT p.blog_id FROM {$wpdb->base_prefix}ospn_podcasts p WHERE p.active = 1";
            if ($podcast != "all") $sql = "{$sql} AND p.podcast_slug = %s";
            $sql = "{$sql} ORDER BY p.podcast_slug ASC";
            $this->ids = $wpdb->get_results($wpdb->prepare($sql, $podcast));
        } else {

        }
        $this->index = 0;

        if ($hosts != null) {
            
        }
    }

    /**
     * @return bool
     */
    public function have_podcasts()
    {
        return ($this->ids != null) && sizeof($this->ids) > 0 && $this->index < sizeof($this->ids);
    }

    /**
     *
     */
    public function the_podcast()
    {
        /** @global \wpdb $wpdb */
        global $wpdb;

        /** @var int $blog_id */
        $blog_id = $this->ids[$this->index]->blog_id;

        /** @var array|null $results */
        $results = $wpdb->get_results(<<<TAG
SELECT
	p.*
FROM
	wp_ospn_podcasts p
where
	p.blog_id = ${blog_id};
TAG
        );
        $properties = array("blog_id", "podcast_name", "podcast_slug", "website", "contact", "podcast_feed", "active", "tagline", "logo", "description");
        foreach ($properties as $property) {
            $this->$property = $results[0]->$property;
        }

        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}ospn_podcast_hosts WHERE podcast_id = %d ORDER BY sequence ASC", $blog_id));
        $this->hosts = $results;
        $this->index = $this->index + 1;
        $this->host_index = 0;
    }

    /**
     * @param bool $echo
     * @return string
     */
    public function the_logo($echo = true)
    {
        if ($echo) {
            echo $this->logo;
        }
        return $this->logo;
    }

    /**
     * @param bool $echo
     * @return string
     */
    public function the_name($echo = true)
    {
        if ($echo) {
            echo $this->podcast_name;
        }
        return $this->podcast_name;
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
     * @param bool $echo
     * @return string
     */
    public function the_permalink($echo = true)
    {
        /** @var string $permalink */
        $permalink = get_home_url(null, "/podcasts/{$this->podcast_slug}");
        if ($echo) {
            echo $permalink;
        }
        return $permalink;
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
    public function the_hosts_title($echo = true) {
        $title = sizeof($this->hosts) == 1 ? __("The Host") : __("The Hosts");
        if ($echo) {
            echo $title;
        }
        return $title;
    }

    public function have_hosts() {
        return ($this->host_index < sizeof($this->hosts));
    }

    public function the_host() {
        $this->host = get_userdata($this->hosts[$this->host_index]->host_id);
        $this->host_index = $this->host_index + 1;
    }

    public function the_host_name($echo = true) {
        if ($echo) {
            echo $this->host->display_name;
        }
        return $this->host->display_name;
    }

    public function the_host_url($echo = true) {
        if ($echo) {
            echo $this->host->user_url;
        }
        return $this->host->user_url;
    }

    public function the_host_bio($echo = true) {
        if ($echo) {
            echo $this->host->user_description;
        }
        return $this->host->user_description;
    }

    public function the_host_avatar($size = 125, $echo = true) {
        $avatar = get_avatar($this->host->ID, $size);
        if ($echo) {
            echo $avatar;
        }
        return $avatar;
    }

    public function the_host_permalink($echo = true) {
        $url = get_author_posts_url($this->host->ID);
        if ($echo) {
            echo $url;
        }
        return $url;
    }
}
