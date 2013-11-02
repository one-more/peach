<?php
/**
 * Class request - contains request params
 */
class request {

	/**
	 * @var request params
	 */
	public $params = [];

	/**
	 * initialise array
	 */
	public function __construct() {
		$this->params = $_REQUEST;
	}

	/**
	 * @return request
	 */
	public function get_params() {
		return $this->params;
	}
}