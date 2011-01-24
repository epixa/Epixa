<?php
/**
 * Epixa Library
 */

namespace Epixa\Controller\Helper;

use Zend_Controller_Action_Helper_Abstract as AbstractHelper,
    BadMethodCallException,
    Exception;

/**
 * Controller helper that allows for various forms of assertion functionality
 *
 * @category   Epixa
 * @package    Controller
 * @subpackage Helper
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Assert extends AbstractHelper
{
    /**
     * Magic method -- invoked when a called method is not found
     * 
     * If the given method does not exist, checks to see if an appropriate 
     * assertion method can be found in the current class.  Assertion methods 
     * are protected, so this is the only way to invoke them publicly.
     * 
     * If an assertion is found but returns false, then an exception is thrown. 
     * The type of exception thrown and exception message can be customized on 
     * method invokation. e.g.:
     * 
     * $helper->isset($value, '\LogicException', 'Value does not exist')
     * 
     * @param  string $method
     * @param  array  $arguments
     * @throws BadMethodCallException If assertion method does not exist
     * @throws Exception              If assertion method returns false
     */
    public function __call($method, array $arguments)
    {
        $methodName = $this->_formatAssertMethodName($method);
        if (!method_exists($this, $methodName)) {
            throw new BadMethodCallException(sprintf(
                'Assertion `%s` does not exist', $method
            ));
        }
        
        $value = array_shift($arguments);
        if (!$this->$methodName($value)) {
            $exceptionName = array_shift($arguments);
            $message       = array_shift($arguments);
            $this->throwException($exceptionName, $message);
        }
    }
    
    /**
     * Throws an exception with an optional type and message
     * 
     * If $exceptionName is specified, throws an exception of that type.  
     * Otherwise, throws a standard Exception.
     * 
     * Passes any provided message along to the exception.
     * 
     * @param null|string $exceptionName
     * @param null|string $msg
     */
    public function throwException($exceptionName = null, $msg = null)
    {
        if (!$exceptionName) {
            $exceptionName = 'Exception';
        }
        
        throw new $exceptionName($msg);
    }
    
    
    /**
     * Determines if the given property is actually set to something
     * 
     * @link http://php.net/isset
     * 
     * @param  mixed $value
     * @return boolean
     */
    protected function _assertIsset($value)
    {
        return isset($value);
    }
    
    /**
     * Formats the given name as an assertion method name
     * 
     * @param  string $name
     * @return string 
     */
    protected function _formatAssertMethodName($name)
    {
        return '_assert' . ucfirst($name);
    }
}