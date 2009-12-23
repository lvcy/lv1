<?php

class Form_User_Login extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName('loginUser');

        $id = new Zend_Form_Element_Hidden('id');

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('User name')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');
        
        $passsword = new Zend_Form_Element_Password('password');
        $passsword->setLabel('Password')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty')
        ->addValidator('stringLength', true, array(6));
        

        $submit = new Zend_Form_Element_Submit('login');
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($id, $username, $passsword, $submit));
    }
}
?>