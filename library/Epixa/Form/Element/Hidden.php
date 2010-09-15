<?php
/**
 * Epixa Library
 */

namespace Epixa\Form\Element;

/**
 * Extension of Zend_Form_Element_Hidden that overrides the default element
 * decorators
 *
 * @category   Epixa
 * @package    Form
 * @subpackage Element
 * @copyright  2010 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Hidden extends \Zend_Form_Element_Hidden
{
    /**
     * {@inheritdoc}
     *
     * Remove all decorators other than the view helper that renders the hidden
     * field itself.
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->setDecorators(array('ViewHelper'));
        }
    }
}