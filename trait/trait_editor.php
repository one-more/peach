<?php
/**
 * Class trait_editor
 *
 * @author Nikolaev D.
 */
trait trait_editor {
    /**
     * @return mixed|void
     */
    public static function start()
    {
        if(!empty($_REQUEST['task'])) {
            $task = $_REQUEST['task'];

            $params = !empty($_REQUEST['params'])? $_REQUEST['params'] : null;

            static::$task($params);
        }
    }
}