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
        if(file_exists($path) || is_dir($path)) {
            $iterator = new FilesystemIterator($path);

            foreach($iterator as $el) {
                if(is_dir($el)) {
                    static::remDir($el);
                }
                else {
                    unlink($el);
                }
            }
            if(file_exists($path)) {
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
            return htmlspecialchars(htmlentities(trim($arr)));
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
     * @param $name
     * @return string
     */
    public static function get_filename($name)
    {
        $arr = preg_split('/\./', $name);

        $name = $arr[0];

        $ext = null;

        if(!empty($arr[1]))
            $ext = $arr[1];

        if(strlen($name) > 7) {
             $name = substr($name, 0 ,6);
        }

        if($ext)
            return $name.'.'.$ext;
        else
            return $name;
    }
}