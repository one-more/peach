<?php
class installer {
    public static $path = "../extensions/installer/";
    public static $controllers = [];
    public static $models = [];

    /**
     * @param $name - name of the controller
     * @return mixed
     */
    public static function getAdminController($name) {
        if(!in_array($name, array_keys(self::$controllers))) {
            require_once(self::$path."admin/controllers/$name.php");

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
    public static function getAdminModel($name) {
        if(!in_array($name, array_keys(self::$models))) {
            require_once(self::$path."admin/models/$name.php");

            $model = $name.'model';

            self::$models[$name] = new $model;

            return  self::$models[$name];
        }
        else {
            return  self::$models[$name];
        }
    }

    /**
     * install site
     */
    public static function installSite() {
        $controller = static::getAdminController('site');

        $controller->exec('display');
    }
}