<?php
/**
 * entry point of extension
 *
 * contains base methods of extension
 *
 * Class trait_extension
 *
 * @author Nikolaev D
 */
trait trait_extension {
	/**
	 * returns object of admin controller
	 *
	 * @param $name - name of the controller without suffix e.c. site - sitecontroller
	 * @return mixed
	 */

	/**
	 * @var array of admin controllers
	 */
	private static $controllers = [];

	/**
	 * @var array of admin models
	 */
	private static $models = [];

	/**
	 * @var array of site controllers
	 */
	private static $site_controllers = [];

	/**
	 * @var array of site models
	 */
	private static $site_models = [];

    /**
     * @var null name of called class
     */
    private static $name = null;

    /**
     * @var null path to extension
     */
    private static $path = null;

    /**
     * @var null path to cache folder
     */
    private static $cache_path = null;

	private static function getAdminController($name) {
		if(!in_array($name, array_keys(static::$controllers))) {
			require_once(static::$path."admin/controllers/$name.php");

			$controller = $name.'controller';

			static::$controllers[$name] = new $controller;
		}

		return static::$controllers[$name];
	}

	/**
	 * @param $name - name of the models without suffix e.c. site - sitemodel
	 * @return object of model class
	 */
	public static function getAdminModel($name) {
		if(!in_array($name, array_keys(static::$models))) {
			require_once(static::$path."admin/models/$name.php");

			$model = $name.'model';

			$ini = factory::getIniServer();

			$dbs = [];

			$dbs['db_user'] = $ini->read('db_settings', 'db_user');
			$dbs['db_pass'] = $ini->read('db_settings', 'db_pass');
			$dbs['db_name'] = $ini->read('db_settings', 'db_name');

			static::$models[$name] = new $model($dbs['db_name'], $dbs['db_user'], $dbs['db_pass']);
		}

		return static::$models[$name];
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	private static function getSiteController($name) {
		if(!in_array($name, array_keys(static::$site_controllers))) {
			require_once(static::$path."site/controllers/$name.php");

			$controller = $name.'controller';

			static::$site_controllers[$name] = new $controller;
		}

		return static::$site_controllers[$name];
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public static function getSiteModel($name) {
		if(!in_array($name, array_keys(static::$site_models))) {
			require_once(static::$path."site/models/$name.php");

			$model = $name.'model';

			$ini = factory::getIniServer();

			$dbs = [];

			$dbs['db_user'] = $ini->read('db_settings', 'db_user');
			$dbs['db_pass'] = $ini->read('db_settings', 'db_pass');
			$dbs['db_name'] = $ini->read('db_settings', 'db_name');

			static::$site_models[$name] = new $model($dbs['db_name'], $dbs['db_user'], $dbs['db_pass']);
		}

		return static::$site_models[$name];
	}

	public static function start() {

        static::$name = get_called_class();

        static::$path = '../extensions/'.static::$name.'/';

        static::$cache_path = static::$path.'/cache/';

        if(file_exists(static::$cache_path))
        {
            static::clear_cache();
        }

		switch(core::$_mode) {
			case 'admin':
				$controller = static::$default_admin_controller;

                $defaults = [
					'controller'	=>	$controller ? $controller : 'default',
					'task'			=>	'display',
					'params'		=> null
				];

				$data = array_merge($defaults, $_REQUEST);

				$controller = static::getAdminController($data['controller']);

				echo $controller->exec($data['task'], $data['params']);
				break;
			case 'site':
				$controller = static::$default_site_controller;

                $defaults = [
					'controller'	=>	$controller ? $controller : 'default',
					'task'			=>	'display',
					'params'		=> null
				];

				$data = array_merge($defaults, $_REQUEST);

				$controller = static::getSiteController($data['controller']);

				echo $controller->exec($data['task'], $data['params']);
				break;
		}
	}

	/**
	 * clears cache every 8 hours
	 */
	private static function clear_cache() {
		if(!file_exists(static::$cahe_path.'cache.ini')) {
			$fp = fopen(static::$cahe_path.'cache.ini', 'a+b');
			fclose($fp);

			$ini = factory::getIniServer(static::$cahe_path.'cache.ini');
			$ini->write('cache_options', 'last_update', time());
			$ini->updateFile();

			return;
		}

		$ini = factory::getIniServer(static::$cahe_path.'cache.ini');

		$upd = $ini->read('cache_options', 'last_update');

		if(time() > ($upd + 3600*60*8)) {
			$dirhandle = opendir(static::$cache_path);

			while(false != ($file = readdir($dirhandle)))
			{
				unlink($file);
			}

			$ini->write('cache_options', 'last_update', time());
			$ini->updateFile();
		}
	}
}