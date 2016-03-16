<?php
class OSPN_Member_Query {

	private $results;

	public function __construct($query = null) {
		if (!empty($query)) {
			$this->prepare_query($query);
			$this->query();
		}
		$this->results = array(
			0 => array('name' => 'Euterpia Radio', 'url' => 'https://www.euterpia-radio.fr'), 
			1 => array('name' => 'The Bugcast', 'url' => 'http://thebugcast.org')
		);
	}

	public function get_results() {
		return $this->results;
	}

	public function get_total() {
		return 2;
	}

}
