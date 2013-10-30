<?php
class supercontroller
{
	public function exec($task, $params = null)
	{
		$arr = $this->$task($params);

        if(!empty($_REQUEST['ajax'])) {
            echo json_encode($arr);
        }
        else {
            echo $arr;
        }
	}
}
?>