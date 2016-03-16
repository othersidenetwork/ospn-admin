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

    function usort_reorder($a, $b)
    {
        // If no sort, default to name
        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'name';
        // If no order, default to asc
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
        // Determine sort order
        $result = strcmp($a[$orderby], $b[$orderby]);
        // Send final sort direction to usort
        return ($order == 'asc') ? $result : -$result;
    }

    function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $query = new OSPN_Member_Query();
        $this->items = $query->get_results();
    }

    function column_default($item, $column_name)
    {
        switch($column_name) {
            case 'name':
            case 'url':
                return $item[$column_name];
            default:
                return print_r($item, true); // Shwo the whole array for troubleshooting purposes
        }
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'name' => array('name', false),
            'url' => array('url', false)
        );
        return $sortable_columns;
    }

    /*
	public function __construct($args = array()) {
		parent::__construct(array(
			'singular' => 'member',
			'plural' => 'members',
			'screen' => null
		));
	}

	public function prepare_items() {
		$per_page = 'members_per_page';
		$members_per_page = $this->get_items_per_page($per_page);
		$paged = $this->get_pagenum();

        / *
		$args = array(
			'number' => $members_per_page,
			'offset' => ($paged - 1) * $members_per_page,
			'include' => wp_get_users_with_no_role(),
			'search' => '',
			'fields' => 'all_with_meta'
		);
		* /
		$wp_member_search = new OSPN_Member_Query();
		$this->items = $wp_member_search->get_results();
		$this->set_pagination_args(array(
			'total_items' => $wp_member_search->get_total(),
			'per_page' => $members_per_page,
		));
	}

	public function get_columns() {
		$c = array(
			'cb'       => '<input type="checkbox" />',
			'name'     => __( 'Name' ),
			'url'      => __( 'URL' )
		);

		error_log("c = " . json_encode($c), 4);
		return $c;
	}

	protected function get_sortable_columns() {
		$c = array(
			'name'     => 'name',
			'url'      => 'url'
		);

		return $c;
	}

	protected function get_default_primary_column_name() {
		return 'name';
	}
    */
}
