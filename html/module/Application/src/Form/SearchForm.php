<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class SearchForm extends Form
{

    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->addElements();
    }

    public function addElements()
    {
        $image = new Element\File("image-file");
        $image->setLabel("Image Upload");
        $image->setAttribute("id", "searchImage");

        $this->add($image);
    }

}