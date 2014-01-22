<?php
/**
 * extension for installation
 *
 * Class installer
 *
 * @author Nikolaev D.
 */
class installer implements widget_extension_interface{
    use trait_widget_extension;

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
        if(empty(static::$controllers[$name])) {
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
        if(empty(static::$models[$name])) {
            require_once(self::$path."admin/models/$name.php");

            $model = $name.'model';

            $ini = factory::getIniServer(SITE_PATH.'configuration.ini');

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
        if(!empty($_REQUEST['method']))  {
            $defaults = [
                'params'    => ''
            ];

            $data = array_merge($defaults, $_REQUEST);

            $method = $_REQUEST['method'];

            static::$method($data['params']);
        }
        else {
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

    /**
     * @return array|mixed
     */
    public static function get_extensions()
    {
        $controller = static::getAdminController('default');

        $arr = $controller->exec('get_extensions');

        return is_array($arr)? $arr : json_decode($arr, true);
    }

    /**
     * @return array|mixed
     */
    public static function get_templates()
    {
        $controller = static::getAdminController('default');

        $arr = $controller->exec('get_templates');

        return is_array($arr)? $arr : json_decode($arr, true);
    }

    /**
     * @return array
     */
    public static function get_info()
    {
        $controller = static::getAdminController('default');

        $alias = $controller->getLang('info')['alias'];

        return ['alias'=>$alias, 'icon'=>null, 'submenu'=>null];
    }
}