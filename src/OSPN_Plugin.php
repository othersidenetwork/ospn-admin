<?php
/**
 * Created by IntelliJ IDEA.
 * User: yannick
 * Date: 28.03.16
 * Time: 22:33
 */

namespace OSPN;

use OSPN\tags\OSPN_Contact;
use OSPN\Tags\OSPN_Host;
use OSPN\Tags\OSPN_Podcast;

class OSPN_Plugin extends OSPN_Base
{
    /** @var array[int] $ids */
    private $ids;
    /** @var int $index */
    private $index;

    /** @var  array $hosts */
    public $hosts;
    /** @var  int $host_index */
    private $host_index;

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
            $sql = "SELECT DISTINCT(h.host_id) FROM {$wpdb->base_prefix}ospn_podcast_hosts h, {$wpdb->users} u WHERE u.ID = h.host_id ORDER BY u.display_name ASC";
            $results = $wpdb->get_results($sql);
            $this->hosts = $results;
            $this->host_index = 0;
        }
        $this->index = 0;
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

        $podcast = new OSPN_Podcast($results[0]);
        $podcast->contacts = array();
        foreach(wp_get_user_contact_methods() as $meta_key => $value) {
            $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}ospn_podcast_meta WHERE podcast_id = %d AND meta_key = %s", $podcast->blog_id, $meta_key));
            if (is_object($row) && $row->meta_value != '') {
                $podcast->contacts[] = new OSPN_Contact($meta_key, $row->meta_value);
            }
        }
        $podcast->contact_index = 0;
        $this->index = $this->index + 1;
        return $podcast;
    }

    public function have_hosts() {
        return ($this->host_index < sizeof($this->hosts));
    }

    public function the_host() {
        $host = new OSPN_Host(get_userdata($this->hosts[$this->host_index]->host_id));
        $this->host_index = $this->host_index + 1;
        return $host;
    }

}
