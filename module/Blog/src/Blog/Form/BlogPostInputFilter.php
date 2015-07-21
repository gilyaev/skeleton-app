<?php
/**
 * Created by PhpStorm.
 * User: dmytro.borysovskiy
 * Date: 7/15/2015
 * Time: 2:47 PM
 */

namespace Blog\Form;

use Zend\InputFilter\InputFilter;

class BlogPostInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'title',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 100,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'text',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 20,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'state',
            'required' => false,
        ));
    }
} 