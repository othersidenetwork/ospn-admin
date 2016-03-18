<?php
class OSPN_Member_Query {

	private $results;

	public function __construct($query = null) {
		if (!empty($query)) {
			$this->prepare_query($query);
			$this->query();
		}
		$this->results = array();
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

    public function get_results() {
        $results = $this->results;
        usort($results, array(&$this, 'usort_reorder'));
		return $results;
	}

	public function get_total() {
		return 2;
	}

}
