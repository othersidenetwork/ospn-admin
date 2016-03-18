<?php

if (!class_exists('WP_List_Table')) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

if (!class_exists('OSPN_Member_Query')) {
    require_once(dirname(dirname(dirname(__FILE__))) . '/includes/class-ospn-member-query.php');
}

class OSPN_Members_List_Table extends WP_List_Table
{

    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name'),
            'url' => __('URL')
        );
        return $columns;
    }

    function prepare_items()
    {
        $query = new OSPN_Member_Query();
        $results = $query->get_results();
        $per_page = $this->get_items_per_page('members_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = count($results);
        $found_data = array_slice($results, (($current_page - 1) * $per_page), $per_page);
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page
        ));
        /*
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        */
        $this->_column_headers = $this->get_column_info();
        $this->items = $found_data;
    }

    function column_default($item, $column_name)
    {
        switch($column_name) {
            case 'name':
            case 'url':
                return $item[$column_name];
            default:
                return print_r($item, true); // Show the whole array for troubleshooting purposes
        }
    }

    function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="member[]" value="%s" />', $item['ID']);
    }

    function column_name($item) {
        $actions = array(
            'edit'   => sprintf('<a href="?page=%s&action=%s&member=%s">' . __('Edit') . '</a>', $_REQUEST['page'], 'edit', $item['ID']),
            'delete' => sprintf('<a href="?page=%s&action=%s&member=%s">' . __('Delete') . '</a>', $_REQUEST['page'], 'delete', $item['ID']),
        );
        return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions));
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'name' => array('name', false),
            'url' => array('url', false)
        );
        return $sortable_columns;
    }

    function get_bulk_actions()
    {
        $actions = array(
            'delete' => __('Delete')
        );
        return $actions;
    }
}
