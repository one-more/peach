<?php
/**
 * templator
 *
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
        $buffer = preg_replace('/\s+/', ' ', $buffer);

        foreach($params as $key=>$value)
		{			
			if(is_array($value)) {
                foreach($value as $k1=>$v1) {
                    $tmp .= $v1;
                }

                $buffer = preg_replace("/:$key/", $tmp, $buffer, 1);
                $buffer = preg_replace("/&:$key/", $tmp, $buffer);
            }
            else {
                if(preg_match("/%$key(.*)$key%/m", $buffer)) {
                    if(empty($value)) {
                        $buffer = preg_replace(["/%$key/", "/$key%/"], ['', ''], $buffer);
                    }
                    else {
                        $buffer = preg_replace("/%$key(.*)$key%/m", $value, $buffer);
                    }
                }
                else {
                    $buffer = preg_replace("/:$key/", $value, $buffer, 1);
                    $buffer = preg_replace("/&:$key/", $value, $buffer);
                }
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
				include($path.DS.$tpl.'.html');
			else
				include('..'.DS.'html'.DS.$tpl.'.html');
			
			$tmpl = self::prepare(ob_get_contents(),$params);
			
			ob_end_clean();

			return $tmpl;		
		}
		else
		{
			if($path)
				return file_get_contents($path.DS.$tpl.'.html');
			else
				return file_get_contents('..'.DS.'html'.DS.$tpl.'.html');
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

    /**
     * @param $msg
     * @return mixed
     */
    public static function get_warning($msg)
    {
        return preg_replace("/:msg/", $msg, file_get_contents(SITE_PATH.'html'.DS.'warning.html'));
    }
}