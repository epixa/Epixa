<?php
/**
 * Epixa Library
 */

namespace Epixa\Application\Resource;

/**
 * Application resource for setting up module autoloading and running module
 * boostraps.
 *
 * @category  Epixa
 * @package   Application
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Discuss/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class Modules extends \Zend_Application_Resource_Modules
{
    /**
     * Initialize the application's modules
     *
     * Set up the autoloader for all modules.  Call all module bootstraps that
     * exist.
     *
     * @return \ArrayObject
     */
    public function init()
    {
        $bootstrap = $this->getBootstrap();
        $bootstrap->bootstrap('FrontController');
        $front = $bootstrap->getResource('FrontController');

        foreach ($front->getControllerDirectory() as $name => $path) {
            $this->setupModule($name, dirname($path));
        }

        return $this->_bootstraps;
    }

    /**
     * Set up the module with the given name and path
     * 
     * @param string $name
     * @param string $path
     */
    public function setupModule($name, $path)
    {
        $this->_addToAutoloader($name);
        $this->_bootstrapModule($name, $path);
    }

    /**
     * Add the given name as a 5.3 namespace to the autoloader
     *
     * @param string $name
     */
    protected function _addToAutoloader($name)
    {
        \Zend_Loader_Autoloader::getInstance()
            ->registerNamespace(sprintf('%s\\', $this->_formatModuleName($name)));
    }

    /**
     * Call the module bootstrap, if it exists.
     * 
     * @param string $name
     */
    protected function _bootstrapModule($name, $path)
    {
        $bootstrapFile = sprintf(
            '%s' . DIRECTORY_SEPARATOR . 'Bootstrap.php',
            $path
        );
        if (!file_exists($bootstrapFile)) {
            return;
        }
        
        $bootstrapClass = $this->_getModuleBootstrapClass($name);
        $bootstrap = new $bootstrapClass();
        if (!$bootstrap instanceof \Zend_Application_Bootstrap_Bootstrapper) {
            throw new \LogicException('Module bootstraps must implement Zend_Application_Bootstrap_Bootstrapper');
        }

        $bootstrap->bootstrap();
        $this->_bootstraps[$module] = $bootstrap;
    }

    /**
     * Get the class of the given module's bootstrap
     *
     * @param  string $name
     * @return string
     */
    protected function _getModuleBootstrapClass($name)
    {
        return sprintf('%s\\Bootstrap', $this->_formatModuleName($name));
    }
}