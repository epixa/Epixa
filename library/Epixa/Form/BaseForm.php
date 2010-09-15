<?php
/**
 * Epixa Library
 */

namespace Epixa\Form;

/**
 * Extension of Zend_Form that adds various Epixa loader paths and changes the
 * way forms are rendered by default
 *
 * @category  Epixa
 * @package   Form
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class BaseForm extends \Zend_Form
{
    /**
     * Constructor
     *
     * Registers form view helper as decorator
     *
     * @param mixed $options
     * @return void
     */
    public function __construct($options = null)
    {
        parent::__construct($options);

        if (!isset($options['disableHash'])) {
            $this->addElement('hash', 'csrf');
        }
    }

    /**
     * {@inheritdoc}
     *
     * In addition, make sure the appropriate Epixa paths are set up on any
     * plugin loaders initialized here.
     *
     * @param  null|string $type
     * @return \Zend_Loader_PluginLoader
     */
    public function getPluginLoader($type = null)
    {
        $type = strtoupper($type);
        if (!isset($this->_loaders[$type])) {
            switch ($type) {
                case self::DECORATOR:
                    $namespaceSegment = 'Form\Decorator';
                    $prefixSegment    = 'Form_Decorator';
                    $pathSegment      = 'Form/Decorator';
                    break;
                case self::ELEMENT:
                    $namespaceSegment = 'Form\Element';
                    $prefixSegment    = 'Form_Element';
                    $pathSegment      = 'Form/Element';
                    break;
                default:
                    throw new \Zend_Form_Exception(sprintf('Invalid type "%s" provided to getPluginLoader()', $type));
            }

            $this->_loaders[$type] = new \Zend_Loader_PluginLoader(array(
                sprintf('Zend_%s_', $prefixSegment)       => sprintf('Zend/%s/', $pathSegment),
                sprintf('Epixa\\%s\\', $namespaceSegment) => sprintf('Epixa/%s/', $pathSegment)
            ));
        }

        return $this->_loaders[$type];
    }

    /**
     * {@inheritdoc}
     *
     * The default decorators that are set up here in the parent class are
     * overridden so they no longer include a dl tag wrapper.
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('FormElements')
                 ->addDecorator('Form');
        }
    }
}