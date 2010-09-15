<?php

namespace Core\Form;

use Epixa\Form\BaseForm;

class TestForm extends BaseForm
{
    public function init()
    {
        $this->addElement('text', 'test', array(
            'required' => true,
            'multioptions' => array(
                1 => 'hmm 1',
                2 => 'hmm 2'
            ),
            'label' => 'Test Label',
            'description' => 'This is a test description'
        ));
    }
}