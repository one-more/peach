<?php
class superModel
{
	protected $_db;
	
	public function __construct($dbname, $user, $pass, $dbtype = 'mysql')
	{
		try
		{
			$this->_db = new PDO("$dbtype:host=localhost;dbname=$dbname",$user, $pass);
			$this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->_db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			die($e->getMessage());
		}
	}
	
	public function getAll($table, $start = 0, $perpage = 5)
	{
		try{
			$sth = $this->_db->query("select * from $table limit $start, $perpage");
			
			return $sth->fetchAll();
		}
		catch(PDOException $e)
		{
			die($e->getMessage());
		}
	}
	
	public function getTotal($table)
	{
		try{
			$sth = $this->_db->query("select * from $table");
			
			return $sth->rowCount();
		}
		catch(PDOException $e)
		{
			die($e->getMessage());
		}
	}
	
	public function execute($task)
	{
		try{
			$this->_db->query($task);
		}
		catch(PDOException $e)
		{
			die($e->getMessage());
		}
	}	
}	
?>