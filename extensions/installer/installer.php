<?php
class installer {
	/**
	 * @var string - path to root of extension
	 */
	public static $path = "../extensions/installer/";

	/**
	 * @var array of controllers
	 */
	public static $controllers = [];

	/**
	 * @var array of models
	 */
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

            $ini = factory::getIniServer('../configuration.ini');

            $params = $ini->readSection('db_params');

            self::$models[$name] = new $model($params['db_name'], $params['db_user'], $params['db_pass']);

            return  self::$models[$name];
        }
        else {
            return  self::$models[$name];
        }
    }

    /**
     * entry point of extension
     */
    public static function start()
    {
        $ini = factory::getIniServer('../extensions/installer/installer.ini');

        $installed = $ini->read('site', 'installed', false);

        $defaults = [
            'controller'    => 'site',
            'task'          => $installed ? 'complete' : 'display',
            'params'        => ''
        ];

        $data = array_merge($defaults, $_REQUEST);

        $controller = static::getAdminController($data['controller']);

        echo $controller->exec($data['task'], $data['params']);
    }
}