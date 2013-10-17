<?php
class mailer
{
	public static function send($to,$subject,$message,$headers=null)
	{
		if(mail($to,$subject,$message,$headers))
			return true;
		else
			return false;
	}
	
	public static function send_attach($to,$subject,$msg,$attach,$hdrs)
	{
		if($attach)
		{
			$fp = fopen($attach,'rb');
			
			if(!$fp)
			{
				return false;
			}
			
			$file = fread($fp,filesize($attach));
			fclose($fp);
		}
		
		$name = basename($attach);
		$EOL = "\r\n";
		$boundary = "--".md5(uniqid(time()));
		$headers = "MIME-Version: 1.0;$EOL";
		$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"$EOL";
		$headers .= $hdrs;
		
		$multipart = "--$boundary$EOL";
		$multipart .= "Content-Type: text/html; charset=windows-1251$EOL";
		$multipart .= "Content-Transfer-Encoding: base64$EOL";
		$multipart .= $EOL;
		$multipart .= chunk_split(base64_encode($msg));
		
		$multipart .= "$EOL--$boundary$EOL";
		$multipart .= "Content-Type: application/octet-stream; name=\"$name\"$EOL";
		$multipart .= "Content-Transfer-Encoding: base64$EOL";
		$multipart .= "Content-Disposition: attachment; $filename=\"$name\"$EOL";
		$multipart .= $EOL;
		$multipart .= chunk_split(base64_encode($file));
		$multipart .= "$EOL--$boundary--$EOL";
		
		if(!mail($to,$subject,$multipart,$headers))
		{
			unlink($attach);
			
			return false;
		}
		else
		{
			unlink($attach);
			
			return true;
		}
	}
}
?>