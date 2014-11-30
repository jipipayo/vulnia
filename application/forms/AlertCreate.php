<?php


class Form_AlertCreate extends Zend_Form {


    public function init() {
    // set the method for the display form to POST
    $this->setMethod ( 'post' );

    $this->addElement ( 'text', 'query', array (
        'label' => 'keyword or phrase:',
        'required' => true,
        'filters' => array ('StringTrim'),
        'required' => true
    ));


    $this->addElement ( 'select', 'score', array (
        'label' => 'score (level of importance):',
        'required' => true,
        'attribs' => array(  'score' => 'score'),
        'multioptions' => array(
                    0 => 'any',
                    1 => '> = 1',
                    2 => '> = 2',
                    3 => '> = 3',
                    4 => '> = 4',
                    5 => '> = 5',
                    6 => '> = 6',
                    7 => '> = 7',
                    8 => '> = 8',
                    9 => '> = 9',

                ),
    ));


    // add the submit button
    $this->addElement ( 'submit', 'save alert', array ('label' => 'Save alert' ) );
  }

}
