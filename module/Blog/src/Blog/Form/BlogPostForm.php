<?php
/**
 * Created by PhpStorm.
 * User: dmytro.borysovskiy
 * Date: 7/15/2015
 * Time: 2:47 PM
 */

namespace Blog\Form;

use Zend\Form\Form;

class BlogPostForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('BlogPost');
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new \Blog\Form\BlogPostInputFilter());
        $this->add(
          [
              'type' => 'Zend\Form\Element\Csrf',
              'name' => 'security',
              'options' => array(
                  'csrf_options' => array(
                      'timeout' => 600
                  )
              )
          ]
        );

        $this->add(
          [
            'name' => 'created',
            'type'=> 'hidden',
          ]
        );

        $this->add([
            'name' => 'userId',
            'type' => 'Hidden',
        ]);

        $this->add(
            [
                'name' => 'id',
                'type'=> 'hidden',
            ]
        );

        $this->add(
          [
              'name' => 'title',
              'type' => 'text',
              'options' => [
                  'label' => 'Title'
              ]
          ]
        );

        $this->add(
            [
                'name' => 'text',
                'type' => 'textarea',
                'options' => [
                    'label' => 'Text'
                ]
            ]
        );

        $this->add(
            [
                'name' => 'state',
                'type' => 'checkbox',
                'options' => [
                    'label' => 'published'
                ]
            ]
        );

        $this->add(
            [
                'name' => 'submit',
                'type' => 'submit',
                'options' => [
                    'value' => 'Save',
                    'id' => 'submit_button'
                ]
            ]
        );
    }

} 