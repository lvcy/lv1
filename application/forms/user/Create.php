<?php

class Form_User_Create extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName('createUser');

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
        

        $submit = new Zend_Form_Element_Submit('create');
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($id, $username, $passsword, $submit));
    }
}
?>