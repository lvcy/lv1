<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }
    
    public function addAction()
    {
        $this->view->title = "Create New User";
        $this->view->headTitle($this->view->title, 'PREPEND');

        $form = new Form_Album();
        $form->submit->setLabel('Add');
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $artist = $form->getValue('artist');
                $title = $form->getValue('title');
                $albums = new Model_DbTable_Albums();
                $albums->addAlbum($artist, $title);
                
                $this->_redirect('/');
            } else {
                $form->populate($formData);
            }
        }
    }


}

