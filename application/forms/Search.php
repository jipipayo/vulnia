<?php


class Form_Search extends Zend_Form
{
    private $q;
    private $score;
    private $placeholder;


    private function _randomPlaceholder(){
        //TODO: get most searched queries from db
        $arr = Array( 'php 5.3', 'java', 'mysql 5.0', 'perl 5.12', 'ruby', 'wordpress', 'drupal', 'rails', 'node.js'  );
        return $arr[array_rand($arr)];
    }

    public function init()
    {
        if (isset($_REQUEST['vulnerabilities'])) {
            $this->q = trim(stripcslashes(strip_tags($_REQUEST['vulnerabilities'])));
        };

        if (isset($_REQUEST['score'])) {
            $this->score = (int)$_REQUEST['score'];
        } else {
            $this->score = 0;
        };

        $this->setAction('/search');
        $this->setMethod('get');
        $this->setAttrib("class", "navbar-form navbar-left");

        $this->placeholder = $this->_randomPlaceholder();

        $this->addElement(
            'text', 'vulnerabilities',
            array('value' => $this->q,
                'class' => 'form-control ',
                'placeholder' => $this->placeholder,
                'filters' => array('StringTrim'),
                'validators' => array(array('StringLength', false, array(2, 80)))
            )
        );

        $this->addElement ( 'select', 'score', array (
            'label' => 'score (level of importance):',
            'value' => $this->score,
            'required' => true,
            'attribs' => array( 'score' => 'score'),
            'multioptions' => array(
                        0 => 'any score',
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


        $trackurl = substr(md5(rand(1, 99999)), 0, 6);

        $this->addElement(
            'button', "$trackurl",
            array('label' => 'Search',
                'type' => 'submit',
                'class' => 'btn ')
        );


        foreach ($this->getElements() as $el) {
            $el->removeDecorator('DtDdWrapper');
            $el->removeDecorator('Label');
            $el->removeDecorator('HtmlTag');
        };


        // remove the fucking dl tag zend form class
        $this->setDecorators(
            array(
                'FormElements',
                array('HtmlTag', array('tag' => 'span', 'class' => 'navbar-left')), 'Form',
            )
        );


    }

}
