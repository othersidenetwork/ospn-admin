<?php

namespace OSPN\Table;

use WP_List_Table;

/**
 * Class OSPN_Podcasts_Table
 * @package OSPN\Table
 */
class OSPN_Podcasts_Table extends WP_List_Table
{
    /**
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name'),
            'active' => __('Active'),
            'contact' => __('Contact'),
            'website' => __('Website'),
            'rss' => __('RSS Feed')
        );
        return $columns;
    }

    /**
     *
     */
    function prepare_items()
    {
        global $wpdb;

        $sql = <<<TAG
SELECT
    b.blog_id,
    b.domain,
	p.podcast_name,
	p.website,
	p.contact,
	p.active,
	p.podcast_feed
FROM
	{$wpdb->blogs} b
	LEFT JOIN {$wpdb->base_prefix}ospn_podcasts p ON b.blog_id = p.blog_id
WHERE
	b.blog_id > 1
ORDER BY
	p.podcast_name ASC,
	b.domain ASC
TAG;
        $results = $wpdb->get_results($sql);
        $per_page = 10;
        $current_page = $this->get_pagenum();
        $total_items = count($results);
        $found_data = array_slice($results, (($current_page - 1) * $per_page), $per_page);
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page
        ));
        $this->_column_headers = $this->get_column_info();
        $this->items = $found_data;
    }

    /**
     * @param object $item
     * @param string $column_name
     * @return mixed
     */
    function column_default($item, $column_name)
    {
        switch($column_name) {
            case 'website':
                return $item->website;
            case 'contact':
                return $item->contact;
            case 'active':
                return $item->active == 1 ? __('Yes') : __('No');
            case 'rss':
                return $item->podcast_feed;
            default:
                return print_r($item, true); // Show the whole array for troubleshooting purposes
        }
    }

    /**
     * @param object $item
     * @return string
     */
    function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="podcast[]" value="%s" />', $item->blog_id);
    }

    /**
     * @param $item
     * @return string
     */
    function column_name($item) {
        $actions = array(
            'edit'   => sprintf('<a href="?page=ospn-admin-podcast-edit&podcast=%d">' . __('Edit') . '</a>', $item->blog_id),
            /*'delete' => sprintf('<a href="?page=%s&action=%s&member=%s">' . __('Delete') . '</a>', $_REQUEST['page'], 'delete', $item->blog_id),*/
        );
        return sprintf('%1$s %2$s', $item->podcast_name == null ? $item->domain : $item->podcast_name, $this->row_actions($actions));
    }

    /**
     * @return array
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
        );
        return $sortable_columns;
    }

    /**
     * @return array
     */
    /*
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => __('Delete')
        );
        return $actions;
    }
    */
}