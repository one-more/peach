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
    public static $path = null;

    /**
     * @var null path to cache folder
     */
    private static $cache_path = null;

    /**
     * initialize some static fields
     */
    private static function init()
    {
        static::$name = get_called_class();

        static::$path = '../extensions/'.static::$name.'/';

        static::$cache_path = static::$path.'/cache/';
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function get_admin_controller($name) {

        static::init();

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
	public static function get_admin_model($name) {

        static::init();

        if(!in_array($name, array_keys(static::$models))) {
			require_once(static::$path."admin/models/$name.php");

			$model = $name.'model';

			$ini = factory::getIniServer('../configuration.ini');

			$dbs = $ini->readSection('db_params');

			static::$models[$name] = new $model($dbs['db_name'], $dbs['db_user'], $dbs['db_pass']);
		}

		return static::$models[$name];
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public static function get_site_controller($name) {

        static::init();

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
	public static function get_site_model($name) {

        static::init();

        if(!in_array($name, array_keys(static::$site_models))) {
			require_once(static::$path."site/models/$name.php");

			$model = $name.'model';

            $ini = factory::getIniServer('../configuration.ini');

            $dbs = $ini->readSection('db_params');

			static::$site_models[$name] = new $model($dbs['db_name'], $dbs['db_user'], $dbs['db_pass']);
		}

		return static::$site_models[$name];
	}

	public static function start() {

        static::init();

        if(file_exists(static::$cache_path))
        {
            static::clear_cache();
        }

        //todo костыль
        if(!empty($_REQUEST['method']))  {
            $defaults = [
                'params'    => ''
            ];

            $data = array_merge($defaults, $_REQUEST);

            $method = $_REQUEST['method'];

            static::$method($data['params']);
        }
		else {
            switch(core::$mode) {
                case 'admin':

                    $defaults = [
                        'controller'	=>	'default',
                        'task'			=>	'display',
                        'params'		=> null
                    ];

                    $data = array_merge($defaults, $_REQUEST);

                    $controller = static::get_admin_controller($data['controller']);

                    echo $controller->exec($data['task'], $data['params']);
                    break;
                case 'site':

                    $defaults = [
                        'controller'	=>	'default',
                        'task'			=>	'display',
                        'params'		=> null
                    ];

                    $data = array_merge($defaults, $_REQUEST);

                    $controller = static::get_site_controller($data['controller']);

                    echo $controller->exec($data['task'], $data['params']);
                    break;
            }
        }
	}

	/**
	 * clears cache every 8 hours
	 */
	private static function clear_cache() {

        static::init();

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

    /**
     * @param $section
     * @param null $field
     * @return array|bool|string
     */
    public static function read_params($section, $field = null)
    {
        $name = get_called_class();

        if(file_exists("../extensions/$name/$name".".ini")) {
            $ini = factory::getIniServer("../extensions/$name/$name".'.ini');

            if($field) {
                return $ini->read($section, $field, false);
            }
            else {
                return $ini->readSection($section);
            }
        }
        else {
            return false;
        }
    }

    /**
     * @param $section
     * @param $field
     * @param null $value
     */
    public static function write_params($section, $field, $value = null)
    {
        $name = get_called_class();

        $ini = factory::getIniServer("../extensions/$name/$name".'.ini');

        if($value) {
            $ini->write($section, $field, $value);
        }
        else {
            $ini->writeSection($section, $field);
        }

        $ini->updateFile();
    }
}