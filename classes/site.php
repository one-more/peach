<?php
class site extends supermodel
{
	public function __construct()
	{
		$arr = json_decode(file_get_contents('config.txt'),true);
		
		parent::__construct($arr['dbname'],$arr['dbuser'],$arr['dbpass']);
	}
	
	public function getTemplate()
	{
		try
		{
			$sth = $this->_db->query('select * from templates where active = "1"');
			
			return $sth->fetch();
		}
		catch(PDOException $e)
		{
			die($e->getMessage());
		}
	}
}
?>