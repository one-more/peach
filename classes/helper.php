<?php
class helper
{
	public static function getConfig()
	{
		return file_get_contents('config.txt');
	}
	
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
	
	public static function getSql($filepath)
	{
		$file = file($filepath);
		$sql = array();
		$i = 0;
		foreach($file as $line)
		{
			if(!trim($line))
			{
				$i++;
			}
			else
			{
				$sql[$i] .= stripcslashes($line);
			}
		}
		
		return $sql;
	}	
}
?>