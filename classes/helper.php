<?php
/**
 * contains helper methods
 *
 * Class helper
 *
 * @author Nikolaev D.
 */
class helper
{
    /**
     * @return array
     */
    public static function get_jquery_tabs_js()
    {
        return [
            dom::create_element('script', ['src'=>'/js/ui/minified/jquery.ui.core.min.js']),
            dom::create_element('script', ['src'=>'/js/ui/minified/jquery.ui.widget.min.js']),
            dom::create_element('script', ['src'=>'/js/ui/minified/jquery.ui.tabs.min.js'])
        ];
    }

    /**
     * @param $path
     */
    public static function remDir($path)
	{
		if(file_exists($path)&& is_dir($path))
		{
			$dirhandle = opendir($path);
			while(false !== ($file = readdir($dirhandle)))
			{
				if($file != '.' && $file != '..')
				{
					$tmppath = $path.'/'.$file;
					chmod($tmppath,0777);
					if(is_dir($tmppath))
					{
						self::remDir($tmppath);
					}
					else
					{
						if(file_exists($tmppath))
						{
							unlink($tmppath);
						}
					}
				}
			}
			
			closedir($dirhandle);
			if(file_exists($path))
			{
				rmdir($path);
			}
		}	
	}

    /**
     * @param $total
     * @param $perpage
     * @return null|string
     */
    public function getPagination($total,$perpage)
	{
		$count = $total/$perpage;
		$pg=null;
		for($i=1;$i<($count+1);$i++)
		{
			$pg .= "<li><a href=\"#page$i\">$i</a></li>";
		}
		return $pg;
	}

    /**
     * @param $filepath
     * @return array
     */
    public static function getSql($filepath)
	{
		$file = file($filepath);
		$sql = array();
		$i = 0;
        $sql[0] = '';
		foreach($file as $line)
		{
			if(!trim($line))
			{
				$i++;
                $sql[$i] = '';
			}
			else
			{
				$sql[$i] .= stripcslashes($line);
			}
		}
		
		return $sql;
	}

    /**
     * @param $arr
     * @return string
     */
    public static function purify($arr)
    {
        if(is_array($arr)) {
            foreach($arr as $k=>$v) {
                $arr[$k] = static::purify($v);
            }

            return $arr;
        }
        else {
            return htmlentities(trim($arr));
        }
    }

    /**
     * @param $data
     * @param $path
     * @param $width
     * @param $height
     * @return string
     */
    public static function make_img($data, $path, $width, $height)
    {
        preg_match('/data:image\/(\w+);/', $data, $arr);

        $func = "imagecreatefrom$arr[1]";

        $im = $func($data);

        imagesavealpha($im, true);

        $thmb = imagecreatetruecolor($width, $height);

        $size = ['0' => imagesx($im), '1' => imagesy($im)];

        imagealphablending($thmb, false);
        imagesavealpha($thmb, true);

        imagecopyresampled($thmb, $im, 0,0,0,0, $width, $height, $size[0], $size[1]);

        $name = md5($data);

        $func = "image$arr[1]";

        $func($thmb, '.'.$path.DS.$name.'.'.$arr[1]);

        imagedestroy($im);
        imagedestroy($thmb);

        return $path.DS.$name.'.'.$arr[1];
    }

    /**
     * @param $arr
     * @return array
     */
    public static function delete_empty_values($arr)
    {
        $result = [];

        foreach($arr as $k=>$v) {
            if(!empty($v)) {
               $result[$k] = $v;
            }
        }

        return $result;
    }

    /**
     * @return mixed|string
     */
    public static function get_create_layout_fieldset()
    {
        $params = factory::get_reference('create_layout_fieldset');

        if(system::get_menu() && system::get_menu() != -1) {
            $params['fieldset'] = '';
        }
        else {
            $params['fieldset'] = templator::get_warning($params['NO_MENU']);
        }

        return templator::getTemplate(
            'create_layout_fieldset',
            $params,
            SITE_PATH.'html'
        );
    }
}