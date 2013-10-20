<?php
class supercontroller
{
	public function exec($task, $params = null)
	{
		$this->$task($params);
	}
}
?>