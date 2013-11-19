<?php
class supercontroller
{
	public function exec($task, $params = null)
	{
		$arr = $this->$task($params);

        if(!empty($_REQUEST['ajax']) && is_array($arr)) {
            return json_encode($arr);
        }
        else {
            return $arr;
        }
	}
}
?>