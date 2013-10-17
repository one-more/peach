<?php
class autoloader
{
	public static function load($class)
	{
		$classname = strtolower($class).'.php';
		
		$file = 'classes/'.$classname;
		
		if(!file_exists($file))
		{
			die("cannot load $classname");
		}
		
		include($file); 
	}
}
?> 