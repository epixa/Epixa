<?php
/**
 * Epixa Library
 */

namespace Epixa\Form\Element;

/**
 * Extension of Zend_Form_Element_Submit that overrides the default element
 * decorators
 *
 * @category   Epixa
 * @package    Form
 * @subpackage Element
 * @copyright  2010 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Submit extends \Zend_Form_Element_Submit
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
                'Tooltip',
                'ViewHelper',
                array(
                    array('field' => 'HtmlTag'),
                    array(
                        'tag'   => 'span',
                        'class' => 'form-element-field form-element-submit'
                    )
                )
            ));
        }
    }
}