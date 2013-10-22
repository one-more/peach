<?php
class autoloader
{
	public static function load($class)
	{
		$classname = strtolower($class).'.php';
		
		$file = '../classes/'.$classname;
		
		if(!file_exists($file))
		{
			file_put_contents('../error.log',date('j.m.Y H:i:s -')." cannot load $classname \r\n", FILE_APPEND);

            echo templator::getTemplate('error', ['error-msg'=>'an error occurred'], '../html');
		}
		
		require_once($file);
	}

    public static function loadExtension($class) {
        $className = strtolower($class).'.php';

        $file = "../extensions/$class/$className";

        if(!file_exists($file)) {
            file_put_contents('../error.log',date('j.m.Y H:i:s -')." cannot load main class for extension $class", FILE_APPEND);

            echo templator::getTemplate('error', ['error-msg'=>'an error occurred'], '../html');
        }

        require_once($file);
    }
}
?> 