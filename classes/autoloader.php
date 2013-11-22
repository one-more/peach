<?php
/**
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
		
		$file = '../classes/'.$classname;
		
		//file dont exists - try load by next func
        if(!file_exists($file))
		{
			//file_put_contents('../error.log',date('j.m.Y H:i:s -')." cannot load $file \r\n", FILE_APPEND);

            //echo templator::getTemplate('error', ['error-msg'=>'an error occurred'], '../html');

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

		$file = "../trait/$class_name.php";

		if(!file_exists($file)) {
			//echo templator::getTemplate('error', ['error-msg'=>'an error occurred'], '../html');

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

        $file = "../interface/$class_name.php";

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

        $file = "../templates/$class_name/$class_name.php";

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

        $file = "../editors/$class_name/$class_name.php";

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

        $file = "../extensions/$class/$className";

        if(!file_exists($file)) {
            file_put_contents('../error.log',date('j.m.Y H:i:s -')." cannot load $file", FILE_APPEND);

            echo templator::getTemplate('error', ['error-msg'=>'an error occurred'], '../html');

            return false;
        }

        require_once($file);
    }
}
?> 