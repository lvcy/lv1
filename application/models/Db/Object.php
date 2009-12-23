<?php
class Model_Db_Object
{
	protected $_db;

	public function __construct()
	{
		// Initialize and retrieve db resource

		$config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', 'production');

		if(!Zend_Registry::isRegistered('db'))
		{
			$db = Zend_Db::factory($config->resources->db);
			
			
			Zend_Registry::set('db', $db);
		}
		$this->_db = Zend_Registry::get('db');
	}

	public function test()
	{
		$stmt = $this->_db->query('CALL usp_up_get_user_profile(1);');
		return $stmt->fetchAll();
	}

}
?>