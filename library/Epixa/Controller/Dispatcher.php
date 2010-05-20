<?php
/**
 * Epixa Library
 */

namespace Epixa\Controller;

/**
 * Extension of Zend_Controller_Dispatcher_Standard that changes expected module
 * format to more of a library-esque structure.
 *
 * @category  Epixa
 * @package   Controller
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Discuss/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class Dispatcher extends \Zend_Controller_Dispatcher_Standard
{
    /**
     * Default controller
     * @var string
     */
    protected $_defaultController = 'Index';

    /**
     * Default module
     * @var string
     */
    protected $_defaultModule = 'Core';


    /**
     * Formats the the provided controller class name with the appropriate
     * namespace.  Loading is left up to the autoloader.
     *
     * @param  string $className
     * @return string Fully qualified controller class name
     */
    public function loadClass($className)
    {
        return $this->formatClassName($this->_curModule, $className);
    }

    /**
     * Format the module name.
     *
     * Unlike in Zend Framework, the default module is always namespaced.
     *
     * @param  string $unformatted
     * @return string
     */
    public function formatModuleName($unformatted)
    {
        return $this->_formatName($unformatted);
    }

    /**
     * Get controller class name
     *
     * Try request first; if not found, try pulling from request parameter;
     * if still not found, fallback to default
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return string|false Returns class name on success
     */
    public function getControllerClass(\Zend_Controller_Request_Abstract $request)
    {
        $controllerName = $request->getControllerName();
        if (empty($controllerName)) {
            if (!$this->getParam('useDefaultControllerAlways')) {
                return false;
            }
            $controllerName = $this->getDefaultControllerName();
            $request->setControllerName($controllerName);
        }

        $className = $this->formatControllerName($controllerName);

        $controllerDirs = $this->getControllerDirectory();
        $moduleName     = $request->getModuleName();

        if ($this->isValidModule($moduleName)) {
            $this->_curModule    = $this->formatModuleName($moduleName);
            $this->_curDirectory = $controllerDirs[$this->_curModule];
        } elseif ($this->isValidModule($this->_defaultModule)) {
            $request->setModuleName($this->_defaultModule);
            $this->_curModule    = $this->_defaultModule;
            $this->_curDirectory = $controllerDirs[$this->_defaultModule];
        } else {
            throw new \Zend_Controller_Exception('No default module defined for this application');
        }

        return $className;
    }

    /**
     * Determine if a given module is valid
     *
     * @param  string $module
     * @return bool
     */
    public function isValidModule($module)
    {
        if (!is_string($module)) {
            return false;
        }

        $module = $this->formatModuleName($module);
        $controllerDir = $this->getControllerDirectory();
        foreach (array_keys($controllerDir) as $moduleName) {
            if ($module == $this->formatModuleName($moduleName)) {
                return true;
            }
        }

        return false;
    }


    /**
     * Format action class name
     *
     * Unlike in Zend Framework, controllers always have Controller in the
     * namespace.
     *
     * @param  string  $moduleName Name of the current module
     * @param  string  $className Name of the action class
     * @return string Formatted class name
     */
    public function formatClassName($moduleName, $className)
    {
        return $this->formatModuleName($moduleName) . '\\Controller\\' . $className;
    }

    /**
     * Formats a string from a URI into a PHP-friendly name.
     *
     * By default, replaces words separated by the word separator character(s)
     * with camelCaps. If $isAction is false, it also preserves replaces words
     * separated by the path separation character with a backslash, making
     * the following word Title cased. All non-alphanumeric characters are
     * removed.
     *
     * @param  string  $unformatted
     * @param  boolean $isAction Defaults to false
     * @return string
     */
    protected function _formatName($unformatted, $isAction = false)
    {
        $implodedSegments = parent::_formatName($unformatted, $isAction);

        return str_replace('_', '\\', $implodedSegments);
    }
}