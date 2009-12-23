<?php
class Model_Util_SpCaller
{
	protected $_db;
	protected $_stmt;

	public function __construct()
	{
		// Initialize and retrieve db resource

		$config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', 'production');

		if(!Zend_Registry::isRegistered('db'))
		{
			$db = Zend_Db::factory($config->resources->db);
			Zend_Registry::set('db', $db);
		}
		$this->_stmt = null;
		$this->_db = Zend_Registry::get('db');
	}

	public static function call($parameters)
	{
		if(strlen($parameters[SP]) > 0)
		{
			$sp = new Model_Util_SpCaller();

			switch ($parameters[SP]) {

				// User management functions
				case 'addUser':
					return $sp->addUser($parameters);
					break;
				case 'authenticateUser':
					return $sp->authenticateUser($parameters);
					break;
				case 'getUserId':
					return $sp->getUserId($parameters);
					break;
				case 'getUserProfile':
					return $sp->getUserProfile($parameters);
					break;
				case 'isUsernameAvailable':
					return $sp->isUsernameAvailable($parameters);
					break;
				case 'editUser':
					return $sp->editUser($parameters);
					break;
				case 'addProfileItem':
					return $sp->addProfileItem($parameters);
					break;
				case 'editProfileItem':
					return $sp->editProfileItem($parameters);
					break;

					// Role management functions
				case 'addRole':
					return $sp->addRole($parameters);
					break;
				case 'editRole':
					return $sp->editRole($parameters);
					break;
				case 'getRole':
					return $sp->getRole($parameters);
					break;
				case 'getUserRoles':
					return $sp->getUserRoles($parameters);
					break;
			}
		}
	}

	// User management functions

	public function addUser($parameters)
	{
		if (strlen($parameters[username]) > 0 && strlen($parameters[userPassword]) > 0)
		{
			$this->_stmt = $this->_db->prepare('CALL usp_u_create_user(:username, :userPassword)');
			$this->_stmt->bindParam('username', $parameters[username]);
			$this->_stmt->bindParam('userPassword', $parameters[userPassword]);
			$this->_stmt->execute();
			$result = $this->_stmt->fetchAll();
			$this->_stmt->closeCursor();
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			{
				$sp->_db->closeConnection();
			}
			return $result;
		}
	}


	public function authenticateUser($parameters)
	{
		if (strlen($parameters[username]) > 0 && strlen($parameters[userPassword]) > 0)
		{
			$this->_stmt = $this->_db->prepare('CALL usp_u_authenticate(:username, :userPassword)');
			$this->_stmt->bindParam('username', $parameters[username]);
			$this->_stmt->bindParam('userPassword', $parameters[userPassword]);
			$this->_stmt->execute();
			$result = $this->_stmt->fetchAll();
			$this->_stmt->closeCursor();
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			{
				$this->_db->closeConnection();
			}
			return $result;
		}
	}


	public function getUserId($parameters)
	{
		$this->_stmt = $this->_db->prepare('CALL usp_u_get_user_id(:username, :userPassword)');
		$this->_stmt->bindParam('username', $parameters[username]);
		$this->_stmt->bindParam('userPassword', $parameters[userPassword]);
		$this->_stmt->execute();
			$result = $this->_stmt->fetchAll();
			$this->_stmt->closeCursor();
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			{
				$this->_db->closeConnection();
			}
			return $result;
	}


	public function getUserProfile($parameters)
	{
		if ($parameters[userId] > 0)
		{
			$this->_stmt = $this->_db->prepare('CALL usp_up_get_user_profile(:userId)');
			$this->_stmt->bindParam('userId', $parameters[userId]);
			$this->_stmt->execute();
			$result = $this->_stmt->fetchAll();
			$this->_stmt->closeCursor();
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			{
				$this->_db->closeConnection();
			}
			return $result;
		}
	}

	public function isUsernameAvailable($parameters)
	{
		if (strlen($parameters[userName]) > 0)
		{
			$this->_stmt = $this->_db->prepare('CALL usp_u_is_username_available(:username);');
			$this->_stmt->bindParam('username', $parameters[username]);
			$this->_stmt->execute();
			$result = $this->_stmt->fetchAll();
			$this->_stmt->closeCursor();
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			{
				$this->_db->closeConnection();
			}
			return $result;
		}
	}

	public function editUser($parameters)
	{
		if (strlen($parameters[userId]) > 0)
		{
			$this->_stmt = $this->_db->prepare('CALL usp_u_update_user(:userId, :userPassword, :userActivity, :userType)');
			$this->_stmt->bindParam('userId', $parameters[userId]);
			$this->_stmt->bindParam('userPassword', $parameters[userPassword]);
			$this->_stmt->bindParam('userActivity', $parameters[userActivity]);
			$this->_stmt->bindParam('userType', $parameters[userType]);
			$this->_stmt->execute();
			$result = $this->_stmt->fetchAll();
			$this->_stmt->closeCursor();
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			{
				$this->_db->closeConnection();
			}
			return $result;
		}
	}

	public function addProfileItem($parameters)
	{
		if (strlen($parameters[userId]) > 0 && strlen($parameters[key]) > 0)
		{
			$this->_stmt = $this->_db->prepare('CALL usp_up_add_user_profile(:userId, :key, :value)');
			$this->_stmt->bindParam('userId', $parameters[userId]);
			$this->_stmt->bindParam('key', $parameters[key]);
			$this->_stmt->bindParam('value', $parameters[value]);
			$this->_stmt->execute();
			$result = $this->_stmt->fetchAll();
			$this->_stmt->closeCursor();
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			{
				$this->_db->closeConnection();
			}
			return $result;
		}
	}

	public function editProfileItem($parameters)
	{
		if (strlen($parameters[userId]) > 0 && strlen($parameters[key]) > 0)
		{
			$this->_stmt = $this->_db->prepare('CALL usp_up_update_user_profile(:userId, :key, :value)');
			$this->_stmt->bindParam('userId', $parameters[userId]);
			$this->_stmt->bindParam('key', $parameters[key]);
			$this->_stmt->bindParam('value', $parameters[value]);
			$this->_stmt->execute();
			$result = $this->_stmt->fetchAll();
			$this->_stmt->closeCursor();
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			{
				$this->_db->closeConnection();
			}
			return $result;
		}
	}

	// Role management functions

	public function addRole($parameters)
	{
		if (strlen($parameters[userId]) > 0 && strlen($parameters[roleName]) > 0)
		{
			$this->_stmt = $this->_db->prepare('CALL usp_r_create_role(:userId, :roleName)');
			$this->_stmt->bindParam('userId', $parameters[userId]);
			$this->_stmt->bindParam('roleName', $parameters[roleName]);
			$this->_stmt->execute();
			$result = $this->_stmt->fetchAll();
			$this->_stmt->closeCursor();
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			{
				$this->_db->closeConnection();
			}
			return $result;
		}
	}

	public function editRole($parameters)
	{
		if (strlen($parameters[roleId]) > 0 && strlen($parameters[roleName]) > 0)
		{
			$this->_stmt = $this->_db->prepare('CALL usp_r_update_role(:roleId, :roleName, :roleDescription)');
			$this->_stmt->bindParam('roleId', $parameters[roleId]);
			$this->_stmt->bindParam('roleName', $parameters[roleName]);
			$this->_stmt->bindParam('roleDescription', $parameters[roleDescription]);
			$this->_stmt->execute();
			$result = $this->_stmt->fetchAll();
			$this->_stmt->closeCursor();
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			{
				$this->_db->closeConnection();
			}
			return $result;
		}
	}

	public function getRole($parameters)
	{
		if (strlen($parameters[roleId]) > 0)
		{
			$this->_stmt = $this->_db->prepare('CALL usp_r_get_role(:roleId)');
			$this->_stmt->bindParam('roleId', $parameters[roleId]);
			$this->_stmt->execute();
			$result = $this->_stmt->fetchAll();
			$this->_stmt->closeCursor();
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			{
				$this->_db->closeConnection();
			}
			return $result;
		}
	}

	public function getUserRoles($parameters)
	{
		if (strlen($parameters[userId]) > 0)
		{
			$this->_stmt = $this->_db->prepare('CALL usp_ur_get_user_roles(:userId)');
			$this->_stmt->bindParam('userId', $parameters[userId]);
			$this->_stmt->execute();
			$result = $this->_stmt->fetchAll();
			$this->_stmt->closeCursor();
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
			{
				$this->_db->closeConnection();
			}
			return $result;
		}
	}
}

?>