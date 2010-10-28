<?php
/**
 * Epixa Library
 */

namespace Epixa\Loader;

use Zend_Loader_Autoloader_Interface as AutoloaderInterface,
    InvalidArgumentException;

/**
 * Autoloader for application module source files
 *
 * @category  Epixa
 * @package   Loader
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class ModuleAutoloader implements AutoloaderInterface
{
    /**
     * @var array
     */
    protected $_modules = array();


    /**
     * Register the autoloader with the SPL autoload stack
     */
    public function register()
    {
        spl_autoload_register(array($this, 'autoload'));
    }

    /**
     * Add a module to the autoloader
     * 
     * @param  string $module
     * @param  string $path
     * @return ModuleAutoloader *Fluent interface*
     *
     */
    public function addModule($module, $path)
    {
        $this->_modules[$module] = rtrim($path, DIRECTORY_SEPARATOR);
        return $this;
    }

    /**
     * Load the given class if it matches any of the modules
     * 
     * @param string $class
     */
    public function autoload($class)
    {
        foreach ($this->_modules as $module => $path) {
            if (strpos($class, ucfirst($module) . '\\') === 0) {
                $class = substr($class, strlen($module) + 1);
                require $path . DIRECTORY_SEPARATOR
                      . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
                return true;
            }
        }

        return false;
    }
}