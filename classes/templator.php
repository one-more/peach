<?
class templator
{
	public static function prepare($buffer,$params)
	{
		foreach($params as $key=>$value)
		{			
			$buffer = preg_replace("/:$key/","$value",$buffer);
		}
		
		return $buffer;		
	}
	
	public static function getTemplate($tpl, $params = array(), $path = null)
	{
		if($params)
		{			 
			
			ob_start();
			
			if($path)
				include("$path\\$tpl.html");
			else
				include("views\\$tpl.html");
			
			$tmpl = self::prepare(ob_get_contents(),$params);
			
			ob_end_clean();
			
			return $tmpl;		
		}
		else
		{
			if($path)
				return file_get_contents("$path\\$tpl.html");
			else
				return file_get_contents("views\\$tpl.html");
		}		
	}
	
	public static function getReplaced($file, $params)
	{
		ob_start();
		
		include $file;
		
		$tmpl = self::prepare(ob_get_contents(),$params);
		
		ob_end_clean();
		
		return $tmpl;
	}
}	
?> 