<?php
class Model_Db_Role
{
	protected $_roleId;
	protected $_roleName;
	protected $_roleDescription;
	protected $_userId;

	public function __construct($parameters)
	{
		$this->roleId = $parameters[roleId];
		$this->_roleName = $parameters[roleName];
		$this->_roleDescription = $parameters[roleDescription];
		$this->_userId = $parameters[userId];
	}

	public static function addRole($userId, $roleName, $roleDescription)
	{
		$result = Model_Db_User::call(array('userId' 			=> $userId,
											'roleName' 			=> $roleName,
											'roleDescription' 	=> $roleDescription,	
											'SP'		 		=> 'addRole'));
		
		return Model_Db_Role::getRole($result[roleId]);
	}

	public static function getRole($roleId)
	{
		$result = Model_Db_User::call(array('roleId' 			=> $roleId,	
											'SP'		 		=> 'getRole'));
		
		return new Model_Db_Role($result);
	}
	
	public function editRole($roleId, $roleName, $roleDescription)
	{
		$result = Model_Db_User::call(array('roleId' 			=> $roleId,
											'roleName' 			=> $roleName,
											'roleDescription' 	=> $roleDescription,	
											'SP'		 		=> 'editRole'));
	}
}

?>