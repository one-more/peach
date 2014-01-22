<?php
/**
 * parent class for all controllers
 *
 * Class supercontroller
 *
 * @author Nikolaev D.
 */
class supercontroller
{
    /**
     * @param $task
     * @param null $params
     * @return string
     */
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