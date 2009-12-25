<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Auth
 * @subpackage Zend_Auth_Adapter
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: DbTable.php 18066 2009-09-10 18:47:53Z ralph $
 */


/**
 * @see Zend_Auth_Adapter_Interface
 */
require_once './Zend/Auth/Adapter/Interface.php';


/**
 * @see Zend_Auth_Result
 */
require_once './Zend/Auth/Result.php';


/**
 * @category   Zend
 * @package    Zend_Auth
 * @subpackage Zend_Auth_Adapter
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Lv_Ex_StoredProcedureAuthAdapter implements Zend_Auth_Adapter_Interface
{

    /**
     * $_identity - Identity value
     *
     * @var string
     */
    protected $_identity = null;

    /**
     * $_credential - Credential values
     *
     * @var string
     */
    protected $_credential = null;

    /**
     * __construct() - Sets configuration options
     * @param $identity string
     * @param  $credential string
     * @return void
     */
    public function __construct($identity, $credential)
    {
    	$this->_identity = $identity;
    	$this->_credential = $credential;
    }
    
    /**
     * setIdentity() - set the value to be used as the identity
     *
     * @param  string $value
     * @return Zend_Auth_Adapter_DbTable Provides a fluent interface
     */
    public function setIdentity($value)
    {
        $this->_identity = $value;
        return $this;
    }

    /**
     * setCredential() - set the credential value to be used, optionally can specify a treatment
     * to be used, should be supplied in parameterized form, such as 'MD5(?)' or 'PASSWORD(?)'
     *
     * @param  string $credential
     * @return Zend_Auth_Adapter_DbTable Provides a fluent interface
     */
    public function setCredential($credential)
    {
        $this->_credential = $credential;
        return $this;
    }

    /**
     * authenticate() - defined by Zend_Auth_Adapter_Interface.  This method is called to
     * attempt an authenication.  Previous to this call, this adapter would have already
     * been configured with all nessissary information to successfully connect to a database
     * table and attempt to find a record matching the provided identity.
     *
     * @throws Zend_Auth_Adapter_Exception if answering the authentication query is impossible
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
    	$result = Model_Db_User::AuthenticateUser($this->_identity, $this->_credential);
    	if($result)
    	{
    	    return new Zend_Auth_Result(
            Zend_Auth_Result::SUCCESS,
            $this->_identity,
            array()
            );    		
    	} 	
    	else
    	{
    	    return new Zend_Auth_Result(
             Zend_Auth_Result::FAILURE,
            $this->_identity,
            array('username/password combination is not valid')
            );
    	}
    }
    
    public function getResultObject()
    {
    	return Model_Db_User::GetUserProfile(Model_Db_User::GetUserId($this->_identity, $this->_credential));
    }
}