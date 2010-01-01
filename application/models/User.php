<?php
class Model_User extends Lv_Util_OneObject
{
	protected $_username;
	protected $_userType;

	public static function IsUserNameAvailable($username)
	{
		$result = Model_Util_SpCaller::call(array('username' => $username,
												  'SP'		 => 'isUsernameAvailable'));
		if ($result[available] == '0')
		return true;
		else
		return false;
	}

	public static function AuthenticateUser($username, $password)
	{
		$result = Model_Util_SpCaller::call(array('username' 	=> $username,
												  'userPassword'=> $password,
												  'SP'		 	=> 'authenticateUser'));
		if ((int)$result[0][userId] > 0)
		return true;
		else
		return false;
	}

	public static function GetUserId($username, $password)
	{
		$result = Model_Util_SpCaller::call(array('username' 	 => $username,
												  'userPassword' => $password,
												  'SP'			 => 'getUserId'));
		if ((int)$result[0][userId] < 1)
		$result[0][userId] = 0;

		return $result[0][userId];
	}

	public function getProfile()
	{
		if ($this->_id > 0)
		{
			return new self($this->_id);
		}
	}

	public function addUser($parameters)
	{
		$dbParams[Sp] = $this->getSpName();
		$dbParams[_username] = $parameters[_username];
		$dbParams[_userpassword] = $parameters[_userpassword];
		$result =  $this->_dbc->call($dbParams);
		return $result[0][_id];
	}

	public function editUser($userProfile)
	{

	}

	public function addUserProfileItem($parameters)
	{
		if(isset($parameters[Sp]))
		$dbParams[Sp] = $parameters[Sp];
		else
		$dbParams[Sp] = $this->getSpName();
		
		$dbParams[_id] = $parameters[_id];
		$dbParams[key] = $parameters[key];
		$dbParams[value] = $parameters[value];
		$this->_dbc->call($dbParams);
	}

	public function editUserProfileItem($parameters)
	{
		$parameters[Sp] = $this->getSpName();
		$this->addUserProfileItem($parameters);
	}

	public function getUserRoles($userId)
	{
		$result = Model_Util_SpCaller::call(array('userId' 	 	 => $userId,
												  'SP'			 => 'getUserRoles'));
		return $result;
	}


}
?>