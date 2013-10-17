<?php
class supercontroller
{
	protected $_model;
	
	public function __construct($array){
		$this->_model = new model($array['dbname'],$array['dbuser'],$array['dbpass']);
	}
	
	public function exec($task, $params = null)
	{
		$this->$task($params);
	}
}
?>