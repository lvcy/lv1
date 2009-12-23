<?php

class UserController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}


	public function indexAction()
	{
		// Get a reference to the singleton instance of Zend_Auth
		$auth = Zend_Auth::getInstance();

		if(!$auth->hasIdentity())
		{
			$urlNamespace = new Zend_Session_Namespace('url');
			$urlNamespace->nextUrl = $this->getRequest()->getRequestUri();
			$this->_redirect('/user/login');
		}
		else
		{
			$this->view->user = Zend_Auth::getInstance()->getIdentity();
		}
	}


	public function createAction()
	{
		$form = new Form_User_Create();
		$this->view->form = $form;
		$this->view->headTitle($this->view->title, 'PREPEND');

		$form->create->setLabel('create account');

		if ($this->getRequest()->isPost())
		{
			$formData = $this->_request->getPost();
			if ($form->isValid($formData))
			{
				$username = $form->getValue('username');
				$password = $form->getValue('password');
				$user = Model_Db_User::AddUser($username, $password);

				$this->_redirect('/user/login');
			}
			else
			{
				$form->populate($formData);
			}
		}
	}


	public function loginAction()
	{
		// Get a reference to the singleton instance of Zend_Auth
		$auth = Zend_Auth::getInstance();

		if($auth->hasIdentity())
		{
			$this->_redirect('/user');
		}
		else
		{
			$loginForm = new Form_User_Login();
			$this->view->loginForm = $loginForm;
			$this->view->headTitle($this->view->title, 'PREPEND');

			$loginForm->login->setLabel('login');
			if ($this->getRequest()->isPost())
			{
				$formData = $this->_request->getPost();

				if ($loginForm->isValid($formData))
				{
					$username = $loginForm->getValue('username');
					$password = $loginForm->getValue('password');

					// Set up the authentication adapter
					$authAdapter = new Zend_Auth_Adapter_StoredProcedure($username, $password);

					// Attempt authentication, saving the result
					$result = $auth->authenticate($authAdapter);

					if (!$result->isValid())
					{
						//$this->_redirect('/index/index');
						$this->view->result = $result->isValid();
						// Authentication failed; print the reasons
						foreach ($result->getMessages() as $message)
						{
							echo "$message\n";
						}
					}
					else
					{
						$auth->getStorage()->write($authAdapter->getResultObject());
						$urlNamespace = new Zend_Session_Namespace('url');
						$this->_redirect($urlNamespace->nextUrl);
					}

				}
				else
				{
					$loginForm->populate($formData);
				}
			}
		}
	}


	public function logoutAction()
	{
		$auth = Zend_Auth::getInstance();

		if($auth->hasIdentity())
		{
			$auth->clearIdentity();
		}
	}
}
