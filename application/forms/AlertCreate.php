<?php


class Form_AlertCreate extends Zend_Form {


    public function init() {
    // set the method for the display form to POST
    $this->setMethod ( 'post' );

    $this->addElement ( 'text', 'query', array (
        'label' => 'keyword or phrase:',
        'required' => true,
        'filters' => array ('StringTrim'),
        'validators' => array ('alnum',
                               array ('regex', false, array ('/^[a-z]/i' )),
                               array ('StringLength', false, array (3, 20) ) ),
        'required' => true
    ));



    // add the submit button
    $this->addElement ( 'submit', 'submit', array ('label' => 'Save alert' ) );
  }

}
