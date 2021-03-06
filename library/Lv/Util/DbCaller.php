<?php
class Lv_Util_DbCaller
{
	protected static $_instance = null;
	protected $_db = null;
	protected $_stmt = null;
	protected $_errorCode = 0;

	protected function __construct()
	{}

	protected function __clone()
	{}

	protected function __distruct()
	{
		$this->_db->closeConnection();
	}

	public static function getInstance()
	{
		if (null === self::$_instance)
		{
			self::$_instance = new self();
			if(!self::$_instance->hasConnection())
			{
				self::$_instance->connect();
			}
		}
		return self::$_instance;
	}

	public function connect()
	{
		$config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', 'production');
		$this->_db = Zend_Db::factory($config->resources->db);
		$this->_db->query('SET character_set_client=utf8');
		$this->_db->query('SET character_set_connection=utf8');
		$this->_db->query('SET character_set_database=utf8');
		$this->_db->query('SET character_set_results=utf8');
		$this->_db->query('SET character_set_server=utf8');
	}

	public function hasConnection()
	{
		if($this->_db)
		return true;
		else return false;
	}

	public function beginTransaction()
	{
		$this->_db->beginTransaction();
		$this->_errorCode = 0;
	}

	public function commitTransaction()
	{
		$this->_db->commit();
		$this->_errorCode = 0;
	}

	public function rollBackTransaction()
	{
		$this->_db->rollBack();
		$this->_errorCode = 0;
	}

	public function test()
	{
		$this->_stmt = $this->_db->prepare('call usp_ur_get_user_roles("1")');
		$this->_stmt->execute();
		$result = $this->_stmt->fetchAll();
		$this->_stmt->closeCursor();
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		{
			$this->_db->closeConnection();
		}
		return $result;
	}

	public function call($parameters)
	{
		if (strlen($parameters[Sp]) > 0)
		{
			$sqlString = "CALL $parameters[Sp](";
			unset($parameters[Sp]);
			ksort($parameters);
			foreach ($parameters as $item)
			{
				$sqlString .= "'$item'" . ', ';
			}
			$sqlString = substr($sqlString, 0, strrpos($sqlString, ','));
			$sqlString .= ')';
			echo $sqlString;
/*			$this->_stmt = $this->_db->prepare('CALL usp_ur_get_user_roles(:userId)');
			$this->_stmt->bindParam('userId', $parameters[userId]);
			$this->_stmt->execute();
			$result = $this->_stmt->fetchAll();
			$this->_stmt->closeCursor();
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			{
				$this->_db->closeConnection();
			}
			return $result;*/
		}
	}


}