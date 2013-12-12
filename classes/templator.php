<?
/**
 * Class templator
 *
 * @author Nikolaev D.
 */
class templator
{
    /**
     * @param $buffer
     * @param $params
     * @return mixed
     */
    public static function prepare($buffer,$params)
	{
		$tmp = '';

        foreach($params as $key=>$value)
		{			
			if(is_array($value)) {
                foreach($value as $k1=>$v1) {
                    $tmp .= $v1;
                }

                $buffer = preg_replace("/:$key/", "$tmp", $buffer);
            }
            else {
                $buffer = preg_replace("/:$key/","$value",$buffer);
            }
		}
		
		return $buffer;		
	}

    /**
     * @param $tpl
     * @param array $params
     * @param null $path
     * @return mixed|string
     */
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

    /**
     * @param $file
     * @param $params
     * @return mixed
     */
    public static function getReplaced($file, $params)
	{
		ob_start();
		
		include $file;
		
		$tmpl = self::prepare(ob_get_contents(),$params);
		
		ob_end_clean();
		
		return $tmpl;
	}

    /**
     * @return string
     */
    public static function get_stub()
    {
        return file_get_contents('..'.DS.'html'.DS.'section_under_construction.html');
    }
}