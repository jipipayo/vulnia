<?php


class Form_Contact extends Zend_Form {

    public function init() {

        $this->setMethod ( 'post' );

        // add an email element
        $this->addElement ( 'text', 'email', array ('label' => 'Your email:', 'required' => true, 'filters' => array ('StringTrim' ), 'validators' => array ('EmailAddress' ) ) );

        $this->addElement ( 'textarea', 'message', array ('label' => 'Your message:', 'validators' => array (array ('StringLength', false, array (20, 2000 ) ) ), 'required' => true )

        );

        $this->addElement('captcha', 'captcha', array(
            'label'      => 'Please enter the 4 letters displayed below:',
            'required'   => true,
            'captcha'    => array(
                'captcha' => 'Figlet',
                'wordLen' => 4,
                'timeout' => 300
            )
        ));


        $this->addElement ( 'submit', 'submit', array ('label' => 'Send' , 'class' => 'btn ' ) );

    }
}