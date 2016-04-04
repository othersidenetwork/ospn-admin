<?php

namespace OSPN\Table;

use WP_List_Table;

/**
 * Class OSPN_Podcasts_Table
 *
 * This class represents the podcasts' table, as displayed on the network's admin page.
 *
 * @package OSPN\Table
 */
class OSPN_Podcasts_Table extends WP_List_Table
{
    /**
     * Get the columns to be displayed.
     *
     * @return array
     */
    function get_columns()
    {
        /** @var array $columns */
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
     * Gets the collection of podcasts to be displayed in the table.
     *
     * @return void
     */
    function prepare_items()
    {
        /** @global $wpdb \wpdb */
        global $wpdb;

        /** @var string $sql */
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

        /** @var array $results */
        $results = $wpdb->get_results($sql);

        /** @var int $per_page */
        $per_page = 10;

        /** @var int $current_page */
        $current_page = $this->get_pagenum();

        /** @var int $total_items */
        $total_items = count($results);

        /** @var array $found_data */
        $found_data = array_slice($results, (($current_page - 1) * $per_page), $per_page);

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page
        ));
        $this->_column_headers = $this->get_column_info();
        $this->items = $found_data;
    }

    /**
     * Generic function to get a column's value if no "column_<column_name>" exists.
     *
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
     * Get the "checkbox" column's content.
     *
     * @param object $item
     * @return string
     */
    function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="podcast[]" value="%s" />', $item->blog_id);
    }

    /**
     * Get the "name" column's content.
     *
     * @param $item
     * @return string
     */
    function column_name($item) {
        /** @var array $actions */
        $actions = array(
            'edit'   => sprintf('<a href="?page=ospn-admin-podcast-edit&podcast=%d">' . __('Edit') . '</a>', $item->blog_id)
        );
        return sprintf('%1$s %2$s', $item->podcast_name == null ? $item->domain : $item->podcast_name, $this->row_actions($actions));
    }

    /**
     * Gets the name of all sortable columns.
     *
     * @return array
     */
    function get_sortable_columns()
    {
        /** @var array $sortable_columns */
        $sortable_columns = array(
        );

        return $sortable_columns;
    }
}