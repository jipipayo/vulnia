<?php


class Form_Search extends Zend_Form
{
    private $q;

    public function init()
    {
        if (isset($_REQUEST['vulnerabilities'])) {
            $this->q = trim(stripcslashes(strip_tags($_REQUEST['vulnerabilities'])));
        };

        $this->setAction('/search');
        $this->setMethod('get');
        $this->setAttrib("class", "navbar-form navbar-left");


        $this->addElement(
            'text', 'vulnerabilities',

            array('value' => $this->q,
                'class' => 'form-control ',
                'filters' => array('StringTrim'),
                'validators' => array(array('StringLength', false, array(2, 80)))
            )
        );


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
