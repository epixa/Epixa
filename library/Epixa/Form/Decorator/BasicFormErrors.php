<?php
/**
 * Epixa Library
 */

namespace Epixa\Form\Decorator;

use Zend_View as View,
    Zend_Form_Decorator_Abstract as AbstractDecorator;

/**
 * Form decorator to present errors for forms in a basic format
 *
 * If form has no error messages set specifically on it, but one of its child
 * elements has errors, a generic form error message will be rendered.
 *
 * If form has an error message or messages set specifically on it, regardless
 * of the error-state of its child elements, that specific message or messages
 * will be rendered.
 *
 * @category   Epixa
 * @package    Form
 * @subpackage Decorator
 * @copyright  2010 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class BasicFormErrors extends AbstractDecorator
{
    /**
     * @var string
     */
    protected static $_defaultFallbackMessage = 'There was an error with your form input';

    /**
     * @var string
     */
    protected static $_defaultErrorWrapperTemplate = '<div class="form-errors"><ul>%s</ul></div>';

    /**
     * @var string
     */
    protected static $_defaultMessageTemplate = '<li>%s</li>';

    /**
     * @var string
     */
    protected static $_defaultMessageSeparator = "\n";

    /**
     * @var null|string
     */
    protected $_fallbackMessage = null;

    /**
     * @var null|string
     */
    protected $_errorWrapperTemplate = null;

    /**
     * @var null|string
     */
    protected $_messageTemplate = null;

    /**
     * @var null|string
     */
    protected $_messageSeparator = null;


    /**
     * Set the default separator to render between all messages for all uses of
     * this decorator
     *
     * @param string $separator
     */
    public static function setDefaultMessageSeparator($separator)
    {
        self::$_defaultMessageSeparator = (string)$separator;
    }

    /**
     * Get the default separator to render between all messages for all uses of
     * this decorator
     *
     * @return string
     */
    public static function getDefaultMessageSeparator()
    {
        return self::$_defaultMessageSeparator;
    }

    /**
     * Set the default template for rendering specific messages for all uses of
     * this decorator
     *
     * @param string $template
     */
    public static function setDefaultMessageTemplate($template)
    {
        self::$_defaultMessageTemplate = (string)$template;
    }

    /**
     * Get the default template for rendering specific messages for all uses of
     * this decorator
     *
     * @return string
     */
    public static function getDefaultMessageTemplate()
    {
        return self::$_defaultMessageTemplate;
    }

    /**
     * Set the default template for wrapping the list of error messages for all
     * uses of this decorator
     *
     * @param string $template
     */
    public static function setDefaultErrorWrapperTemplate($template)
    {
        self::$_defaultErrorWrapperTemplate = (string)$template;
    }

    /**
     * Get the default template for wrapping the list of error messages for all
     * uses of this decorator
     *
     * @return string
     */
    public static function getDefaultErrorWrapperTemplate()
    {
        return self::$_defaultErrorWrapperTemplate;
    }

    /**
     * Set the default fallback message for all uses of this decorator
     *
     * @param string $msg
     */
    public static function setDefaultFallbackMessage($msg)
    {
        self::$_defaultFallbackMessage = (string)$msg;
    }

    /**
     * Get the default fallback message for all uses of this decorator
     *
     * @return string
     */
    public static function getDefaultFallbackMessage()
    {
        return self::$_defaultFallbackMessage;
    }

    /**
     * Render errors
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $form = $this->getElement();
        if (!$form instanceof \Zend_Form) {
            return $content;
        }

        if (!$form->isErrors()) {
            return;
        }

        $view = $form->getView();
        if (null === $view) {
            return $content;
        }

        $markup = $this->getErrorMarkup($form->getErrorMessages(), $view);

        switch ($this->getPlacement()) {
            case self::APPEND:
                return $content . $this->getSeparator() . $markup;
            case self::PREPEND:
                return $markup . $this->getSeparator() . $content;
        }
    }

    /**
     * Get the error markup for the given set of messages
     *
     * @param  array $messages
     * @param  View  $view
     * @return string
     */
    public function getErrorMarkup(array $messages, View $view)
    {
        if (empty($messages)) {
            array_push($messages, $this->getFallbackMessage());
        }

        $messageSeparator = $this->getMessageSeparator();
        $errorTemplate    = $this->getErrorWrapperTemplate();
        $messageTemplate  = $this->getMessageTemplate();

        array_walk($messages, function(&$message) use($view, $messageTemplate) {
            $message = sprintf($messageTemplate, $view->escape($message));
        });

        return sprintf($errorTemplate, implode($messageSeparator, $messages));
    }

    /**
     * Get the default separator to render between all messages
     *
     * @return string
     */
    public function getMessageSeparator()
    {
        if ($this->_messageSeparator === null) {
            $this->setMessageSeparator(self::getDefaultMessageSeparator());
        }

        return $this->_messageSeparator;
    }

    /**
     * Set the default separator to render between all messages
     *
     * @param  string $separator
     * @return BasicFormErrors *Fluent interface*
     */
    public function setMessageSeparator($separator)
    {
        $this->_messageSeparator = (string)$separator;

        return $this;
    }

    /**
     * Get the template for rendering specific messages on this form
     *
     * @return string
     */
    public function getMessageTemplate()
    {
        if ($this->_messageTemplate === null) {
            $this->setMessageTemplate(self::getDefaultMessageTemplate());
        }

        return $this->_messageTemplate;
    }

    /**
     * Set the template for rendering specific messages on this form
     *
     * This call will be used to wrap individual messages using a call to
     * sprintf(), so it must contain a single occurance of '%s' (without quotes)
     *
     * @param  string $template
     * @return BasicFormErrors *Fluent interface*
     */
    public function setMessageTemplate($template)
    {
        $this->_messageTemplate = (string)$template;

        return $this;
    }

    /**
     * Get template for wrapping the list of error messages
     *
     * @return string
     */
    public function getErrorWrapperTemplate()
    {
        if ($this->_errorWrapperTemplate === null) {
            $this->setErrorWrapperTemplate(self::getDefaultErrorWrapperTemplate());
        }

        return $this->_errorWrapperTemplate;
    }

    /**
     * Set template for wrapping the list of error messages
     *
     * This call will be used to wrap the parsed list of error messages using a
     * call to sprintf(), so it must contain a single occurance of '%s'
     * (without quotes).
     *
     * @param  string $template
     * @return BasicFormErrors *Fluent interface*
     */
    public function setErrorWrapperTemplate($template)
    {
        $this->_errorWrapperTemplate = (string)$template;

        return $this;
    }

    /**
     * Get the fallback message for this decorator
     *
     * @return string
     */
    public function getFallbackMessage()
    {
        if ($this->_fallbackMessage === null) {
            $this->setFallbackMessage(self::getDefaultFallbackMessage());
        }

        return $this->_fallbackMessage;
    }

    /**
     * Set the fallback message for this decorator
     *
     * If no specific errors are set on the form, then this message is rendered
     * instead.
     *
     * @param  string $msg
     * @return BasicFormErrors *Fluent interface*
     */
    public function setFallbackMessage($msg)
    {
        $this->_fallbackMessage = (string)$msg;

        return $this;
    }
}