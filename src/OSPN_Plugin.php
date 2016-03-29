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

    /** @var  int $blog_id the id of the blog */
    public $blog_id;
    /** @var string $podcast_name podcast name */
    public $podcast_name;
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

    function __construct() {
        global $wpdb;

        $podcasts = get_query_var("podcasts");

        $this->ids = $wpdb->get_results(<<<TAG
SELECT
	p.blog_id
FROM
	wp_ospn_podcasts p
where
	p.active = 1
ORDER BY
	p.podcast_name ASC;
TAG
);
        $this->index = 0;
    }

    /**
     * @return bool
     */
    public function have_podcasts() {
        return ($this->ids != null) && sizeof($this->ids) > 0 && $this->index < sizeof($this->ids);
    }

    /**
     *
     */
    public function the_podcast() {
        /** @global \wpdb $wpdb */
        global $wpdb;

        /** @var int $blog_id */
        $blog_id = $this->ids[$this->index]->blog_id;
        $this->log(json_encode($blog_id));

        $results = $wpdb->get_results(<<<TAG
SELECT
	p.*
FROM
	wp_ospn_podcasts p
where
	p.blog_id = ${blog_id};
TAG
);
        $this->log(json_encode($results));

        $properties = array("blog_id", "podcast_name", "website", "contact", "podcast_feed", "active", "tagline", "logo", "description");
        foreach($properties as $property) {
            $this->$property = $results[0]->$property;
        }

        $this->index = $this->index + 1;

        $this->log(json_encode($this));
    }
}