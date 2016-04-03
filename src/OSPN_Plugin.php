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
    private $hosts;
    /** @var  int $host_index */
    private $host_index;

    /**
     * OSPN_Plugin constructor.
     * @param $podcast string|null if not null, will either be "all" or a podcast slug
     * @param $hosts string|null if not null, will always be "all"
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
            $sql = $wpdb->prepare($sql, $podcast);
            $this->ids = $wpdb->get_results($sql);
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

        /** @var string $sql */
        $sql = $wpdb->prepare("SELECT p.* FROM {$wpdb->base_prefix}ospn_podcasts p WHERE p.blog_id = %d", $blog_id);
        /** @var array|null $results */
        $results = $wpdb->get_results($sql);

        $podcast = new OSPN_Podcast($results[0]);
        $podcast->contacts = array();
        foreach(wp_get_user_contact_methods() as $meta_key => $value) {
            $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}ospn_podcast_meta WHERE podcast_id = %d AND meta_key = %s", $podcast->blog_id, $meta_key));
            if (is_object($row) && $row->meta_value != '') {
                $podcast->contacts[] = new OSPN_Contact($meta_key, $row->meta_value, $value);
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
