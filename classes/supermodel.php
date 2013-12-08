<?php
/**
 * Class superModel
 *
 * @author Nikolaev D.
 */
class superModel
{
    /**
     * @var PDO
     */
    protected $_db;

	use trait_validator;

    /**
     * @param $dbname
     * @param $user
     * @param $pass
     * @param string $dbtype
     */
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
			error::log($e->getMessage());

            echo templator::getTemplate('error', ['error-msg'=>'an exception occurred'], '../html');
		}
	}

    /**
     * @param $table
     * @param int $start
     * @param int $perpage
     * @return array
     */
    public function get_all($table, $start = 0, $perpage = 0)
	{
		try{
			$sth = $this->_db->prepare("select * from $table limit ?,?");
            $sth->bindParam(1, $start, PDO::PARAM_INT);
            $sth->bindParam(2, $perpage, PDO::PARAM_INT);
            $sth->execute();
			
			return $sth->fetchAll();
		}
		catch(PDOException $e)
		{
            error::log($e->getMessage());

            echo templator::getTemplate('error', ['error-msg'=>'an exception occurred'], '../html');
		}
	}

    /**
     * @param $table
     * @return int
     */
    public function get_total($table)
	{
		try{
			$sth = $this->_db->query("select * from $table");
			
			return $sth->rowCount();
		}
		catch(PDOException $e)
		{
            error::log($e->getMessage());

            echo templator::getTemplate('error', ['error-msg'=>'an exception occurred'], '../html');
		}
	}

    /**
     * @param $task
     */
    public function execute($task)
	{
		try{
			$this->_db->query($task);
		}
		catch(PDOException $e)
		{
            error::log($e->getMessage());

            echo templator::getTemplate('error', ['error-msg'=>'an exception occurred'], '../html');
		}
	}	
}