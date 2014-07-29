<?php

class Form_UserForgot extends Zend_Form {
	public function init() {
		// set the method for the display form to POST
		$this->setMethod ( 'post' );

		$this->addElement ( 'text', 'email', array ('label' => 'Your email:', 'required' => true, 'filters' => array ('StringTrim' ), 'validators' => array ('EmailAddress' ) ) );

        $this->addElement('captcha', 'captcha', array(
            'label'      => 'Please enter the 4 letters displayed below:',
            'required'   => true,
            'captcha'    => array(
                'captcha' => 'Figlet',
                'wordLen' => 4,
                'timeout' => 300
            )
        ));

		// add the submit button
		$this->addElement ( 'submit', 'submit', array ('label' => 'Send' ) );
	}
}
