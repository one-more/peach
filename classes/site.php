<?php
/**
 * Class site
 */
class site extends supermodel
{
	/**
	 * @var string - current mode of site e.c. site or admin
	 */
	public static $_mode = 'site';

	public function __construct()
	{
		$ini = factory::getIniServer();

		$dbs = [];

		$dbs['db_name'] = $ini->read('db_settings', 'db_name');
		$dbs['db_user'] = $ini->read('db_settings', 'db_user');
		$dbs['db_pass'] = $ini->read('db_settings', 'db_pass');
		
		parent::__construct($dbs['db_name'],$dbs['db_user'],$dbs['db_pass']);
	}

	/**
	 * @return name of the current template
	 */
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