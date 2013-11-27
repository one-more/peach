<?php
/**
 * Class trait_template
 *
 * @author Nikolaev D.
 */
trait trait_template {
    /**
     * @var array
     */
    private static $admin_controllers = [];

    /**
     * @var array
     */
    private static $site_controllers = [];

    /**
     * @var null name of the template
     */
    public static $name = null;

    /**
     * init some static fields
     */
    private static function init()
    {
        static::$name = get_called_class();
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function get_admin_controller($name)
    {
        static::init();

        if(empty(static::$admin_controllers[$name])) {
            $template = static::$name;

            require_once("../templates/$template/admin/controllers/$name.php");

            $name = $template.'_admin\\'.$name.'controller';

            static::$admin_controllers[$name] = new $name();
        }

        return static::$admin_controllers[$name];
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function get_site_controller($name)
    {
        static::init();

        if(empty(static::$site_controllers[$name])) {
            $template = static::$name;

            require_once("../templates/$template/site/controllers/$name.php");

            $name = $template.'_site\\'.$name.'controller';

            static::$site_controllers[$name] = new $name();
        }

        return static::$site_controllers[$name];
    }

    public static function start()
    {
        static::init();

        $defaults = [
            'controller'    => 'default',
            'task'          => 'display',
            'params'        => ''
        ];

        $data = array_merge($defaults, $_REQUEST);

        switch(core::$mode) {
            case 'admin' :
                $controller = static::get_admin_controller($data['controller']);

                echo $controller->exec($data['task'], $data['params']);

                break;
            case 'site' :
                $controller = static::get_site_controller($data['controller']);

                echo $controller->exec($data['task'], $data['params']);

                break;
        }
    }

    /**
     * @param $section
     * @param null $key
     * @return array|bool|string
     */
    public static function read_params($section, $key = null)
    {
        $name = get_called_class();

        if(file_exists("../templates/$name/$name.ini")) {
            $ini = factory::getIniServer("../templates/$name/$name.ini");

            if($key) {
                return $ini->read($section, $key, false);
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
     * @param $key
     * @param null $value
     */
    public static function write_params($section, $key, $value = null)
    {
        $name = get_called_class();

        $ini = factory::getIniServer("../templates/$name/$name.ini");

        if($value) {
            $ini->write($section, $key, $value);
        }
        else {
            $ini->writeSection($section, $key);
        }
    }

    /**
     * @param $section
     * @param string $lang
     * @return array|bool
     */
    public static function get_lang($section, $lang = 'en-EN')
    {
        static::init();

        $lang2 = site::getLang();

        $path = '..'.DS.'lang'.DS.static::$name.DS.core::$mode.DS.$lang2.'.ini';
        $path2 = '..'.DS.'lang'.DS.static::$name.DS.core::$mode.DS.$lang.'.ini';

        if(file_exists($path)) {
            $ini = factory::getIniServer($path);

            return $ini->readSection($section);
        }
        elseif(file_exists($path2)) {
            $ini = factory::getIniServer($path2);

            return $ini->readSection($section);
        }
        else {
            return false;
        }
    }
}