<?php
/**
 * classes autoloading
 *
 * Class autoloader
 *
 * @author Nikolaev D.
 */
class autoloader
{
    /**
     * @param $class
     * @return bool
     */
    public static function load($class)
	{
        $classname = strtolower($class).'.php';

		$file = '..'.DS.'classes'.DS.$classname;
		
		//file dont exists - try to load by next func
        if(!file_exists($file))
		{

            return false;
		}

		require_once($file);
	}

    /**
     * @param $class
     * @return bool
     */
    public static function loadTrait($class) {
		$class_name = strtolower($class);

		$file = '..'.DS.'trait'.DS."$class_name.php";

		if(!file_exists($file)) {

			return false;
		}

		require_once($file);
	}

    /**
     * @param $class
     * @return bool
     */
    public static function loadInterface($class) {
        $class_name = strtolower($class);

        $file = '..'.DS.'interface'.DS."$class_name.php";

        if(!file_exists($file)) {
            return false;
        }

        require_once($file);
    }

    /**
     * @param $class
     * @return bool
     */
    public static function loadTemplate($class) {
        $class_name = strtolower($class);

        $file = '..'.DS.'templates'.DS.$class_name.DS."$class_name.php";

        if(!file_exists($file)) {
            return false;
        }

        require_once($file);
    }

    /**
     * @param $class
     * @return bool
     */
    public static function loadEditor($class) {
        $class_name = strtolower($class);

        $file = '..'.DS.'editors'.DS.$class_name.DS."$class_name.php";

        if(!file_exists($file)) {
            return false;
        }

        require_once($file);
    }

    /**
     * @param $class
     * @return bool
     */
    public static function loadExtension($class) {
        $className = strtolower($class).'.php';

        $file = '..'.DS.'extensions'.DS.$class.DS."$className";

        if(!file_exists($file)) {
            file_put_contents('..'.DS.'error.log',date('j.m.Y H:i:s -')." cannot load $file \r\n", FILE_APPEND);

            echo templator::getTemplate('error', ['error-msg'=>'an error occurred'], '..'.DS.'html');

            return false;
        }

        require_once($file);
    }
}
?> 