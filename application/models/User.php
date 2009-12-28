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

	public static function GetUserProfile($userId)
	{
		if ($userId > 0)
		{
			$result = Model_Util_SpCaller::call(array('userId'	=> $userId,
										  		  	  'SP'		=> 'getUserProfile'));

			return new Model_Db_User($result);
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
		$result = Model_Util_SpCaller::call(array('userId' 	 	 => $userProfile[userId],
												  'userPassword' => $userProfile[userPassword],
		  										  'userActivity' => $userProfile[userActivity],
		  										  'userType' 	 => $userProfile[userType],
												  'SP'			 => 'editUser'));
	}

	public function addProfileItem($userProfileItem)
	{
		$result = Model_Util_SpCaller::call(array('userId' 	 	 => $userProfileItem[userId],
												  'key' 		 => $userProfileItem[key],
		  										  'value' 		 => $userProfileItem[value],
												  'SP'			 => 'addProfileItem'));
	}

	public function editProfileItem($userProfileItem)
	{
		$result = Model_Util_SpCaller::call(array('userId' 	 	 => $userProfileItem[userId],
												  'key' 		 => $userProfileItem[key],
		  										  'value' 		 => $userProfileItem[value],
												  'SP'			 => 'editProfileItem'));
	}

	public function getUserRoles($userId)
	{
		$result = Model_Util_SpCaller::call(array('userId' 	 	 => $userId,
												  'SP'			 => 'getUserRoles'));
		return $result;
	}

	public function __get($name)
	{
		return $this->$name;
	}

	public function __set($name, $value)
	{
		$this->$name = $value;
	}
}
?>