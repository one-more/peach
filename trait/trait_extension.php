<?php
/**
 * entry point of extension
 *
 * contains base methods of extension
 *
 * Class trait_extension
 *
 * set static variable $clear_cache to clear cache directory every 8 days
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

        static::$path = '..'.DS.'extensions'.DS.static::$name.DS;

        static::$cache_path = static::$path.core::$mode.DS.'cache'.DS;

        if(!file_exists(static::$path.core::$mode)) {
            mkdir(static::$path.core::$mode);
        }

        if(!file_exists(static::$cache_path)) {
            mkdir(static::$cache_path);
        }

        if(!file_exists(static::$path.DS.static::$name.'.ini')) {
            file_put_contents(static::$path.DS.static::$name.'.ini', '');
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function get_admin_controller($name) {

        static::init();

        if(!in_array($name, array_keys(static::$controllers))) {
			require_once(static::$path."admin/controllers/$name.php");

			$controller = static::$name.'_admin\\'.$name.'controller';

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

			$model = static::$name.'_admin\\'.$name.'model';

			$ini = factory::getIniServer(SITE_PATH.'configuration.ini');

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

			$controller = static::$name.'_site\\'.$name.'controller';

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

			$model = static::$name.'_site\\'.$name.'model';

            $ini = factory::getIniServer(SITE_PATH.'configuration.ini');

            $dbs = $ini->readSection('db_params');

			static::$site_models[$name] = new $model($dbs['db_name'], $dbs['db_user'], $dbs['db_pass']);
		}

		return static::$site_models[$name];
	}

	public static function start() {

        static::init();

        if(!empty(static::$clear_cache)) {
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
	 * clears cache every 8 days
	 */
	private static function clear_cache() {

        static::init();

        clearstatcache();

        $iterator = new FilesystemIterator(static::$cache_path);

        foreach($iterator as $el) {
            if(time() > filemtime($el)+3600*24) {
               unlink($el);
            }
        }
	}

    /**
     * @param $section
     * @param null $field
     * @param bool $default
     * @return array|bool|string
     */
    public static function read_params($section, $field = null, $default = false)
    {
        $name = get_called_class();

        if(file_exists("../extensions/$name/$name".".ini")) {
            $ini = factory::getIniServer("../extensions/$name/$name".'.ini');

            if($field) {
                return $ini->read($section, $field, $default);
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

    /**
     * @param $section
     * @param string $default
     * @return array
     */
    public static function read_lang($section, $default = 'en-EN')
    {
        static::init();

        $cur = system::get_current_lang();

        $path1 = '..'.DS.'lang'.DS.static::$name.DS.core::$mode.DS.$cur.'.ini';
        $path2 = '..'.DS.'lang'.DS.static::$name.DS.core::$mode.DS.$default.'.ini';

        if(file_exists($path1)) {
            $ini = factory::getIniServer($path1);

            return $ini->readSection($section);
        }
        elseif(file_exists($path2)) {
            $ini = factory::getIniServer($path2);

            return $ini->readSection($section);
        }
        else {
            return [];
        }
    }
}