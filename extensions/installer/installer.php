<?php
class installer {
    public static $path = "../extensions/installer/";
    public static $controllers = [];
    public static $models = [];

    /**
     * @param $name - name of the controller
     * @return mixed
     */
    public static function getController($name) {
        if(!in_array($name, array_keys(self::$controllers))) {
            require_once(self::$path."controllers/$name.php");

            $controller = $name.'controller';

            self::$controllers[$name] = new $controller;

            return self::$controllers[$name];
        }
        else{
            return self::$controllers[$name];
        }
    }

    /**
     * @param $name - name of the model
     * @return mixed
     */
    public static function getModel($name) {
        if(!in_array($name, array_keys(self::$models))) {
            require_once(self::$path."models/$name.php");

            $model = $name.'model';

            self::$models[$name] = new $model;

            return  self::$models[$name];
        }
        else {
            return  self::$models[$name];
        }
    }
}