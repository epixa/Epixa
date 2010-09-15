<?php
/**
 * Epixa Library
 */

namespace Epixa\Form\Element;

/**
 * Extension of Zend_Form_Element_Select that overrides the default element
 * decorators
 *
 * @category   Epixa
 * @package    Form
 * @subpackage Element
 * @copyright  2010 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Select extends \Zend_Form_Element_Select
{
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->setDecorators(array(
                'ViewHelper',
                array(
                    array('field' => 'HtmlTag'),
                    array(
                        'tag'   => 'span',
                        'class' => 'form-element-field'
                    )
                ),
                'Errors',
                array(
                    'Description',
                    array(
                        'tag'       => 'span',
                        'class'     => 'form-element-description',
                        'placement' => \Zend_Form_Decorator_Abstract::PREPEND
                    )
                ),
                array('Label', array('class' => 'form-label')),
                array(
                    array('element' => 'HtmlTag'),
                    array(
                        'tag'   => 'div',
                        'class' => 'form-element',
                        'id'    => sprintf('form-element-%s', $this->getName())
                    )
                )
            ));
        }
    }
}