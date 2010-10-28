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
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
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
    protected $_defaultModule = 'core';


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