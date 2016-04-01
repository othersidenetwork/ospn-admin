<?php

namespace OSPN;
use OSPN\Form\OSPN_Podcast_Form;
use OSPN\Table\OSPN_Podcasts_Table;

/**
 * Class OSPN_MenuActions
 * @package OSPN
 */
class OSPN_Menu_Actions extends OSPN_Base
{
    /**
     *
     */
    public function podcasts() {
        /** @global \OSPN\Table\OSPN_Podcasts_Table $ospn_podcasts_table */
        global $ospn_podcasts_table;
        $ospn_podcasts_table = new OSPN_Podcasts_Table();
    }

    public function podcast_edit() {
        $this->fill_podcast_form($_REQUEST["podcast"], "admin");
    }

    public function profile() {
        $this->fill_podcast_form(get_current_blog_id(), "profile");
    }

    /**
     * @param $podcast string
     * @param $origin string
     */
    private function fill_podcast_form($podcast, $origin) {
        global $wpdb;

        /** @global \OSPN\Form\OSPN_Podcast_Form $podcast_form */
        global $podcast_form;

        /** @var object $p */
        $p = $wpdb->get_row(<<<TAG
SELECT
	p.*
FROM
	{$wpdb->base_prefix}ospn_podcasts p
WHERE
	p.blog_id = {$podcast};
TAG
);
        $podcast_form = new OSPN_Podcast_Form();
        $podcast_form->blog_id = $p->blog_id;
        $podcast_form->podcast_name = $p->podcast_name;
        $podcast_form->podcast_slug = $p->podcast_slug;
        $podcast_form->tagline = $p->tagline;
        $podcast_form->logo = $p->logo;
        $podcast_form->description = $p->description;
        $podcast_form->website = $p->website;
        $podcast_form->contact = $p->contact;
        $podcast_form->podcast_feed = $p->podcast_feed;
        $podcast_form->active = $p->active;
        $podcast_form->origin = $origin;

        /** @var object $h */
        $h = $wpdb->get_results(<<<TAG
SELECT
    h.*
FROM
    {$wpdb->base_prefix}ospn_podcast_hosts h
WHERE
    h.podcast_id = {$podcast}
ORDER BY
    h.sequence ASC;
TAG
);
        $podcast_form->host_id = $h[0]->host_id;
        if (sizeof($h) > 1) {
            $podcast_form->host2_id = $h[1]->host_id;
        }

        $metas = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}ospn_podcast_meta WHERE podcast_id = %d", $podcast_form->blog_id));
        foreach($metas as $meta) {
            /** @var string $k */
            $k = "contact_{$meta->meta_key}";
            $podcast_form->$k = $meta->meta_value;
        }
    }

}