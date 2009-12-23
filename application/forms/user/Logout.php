<?php

class Form_User_Logout extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName('logoutUser');

        $submit = new Zend_Form_Element_Submit('logout');
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($submit));
    }
}
?>