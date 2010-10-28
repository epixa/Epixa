<?php
/**
 * Epixa Library
 */

namespace Epixa\Application\Resource;

use Epixa\Loader\ModuleAutoloader;

/**
 * Application resource for setting up module autoloading and running module
 * boostraps.
 *
 * @category  Epixa
 * @package   Application
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class Modules extends \Zend_Application_Resource_Modules
{
    /**
     * @var null|ModuleAutoloader
     */
    protected $_autoloader = null;
    

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

        $this->getAutoloader()->register();

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
        // Do not bootstrap the module again
        if (isset($this->_bootstraps[$name])) {
            return;
        }
        
        $this->_addToAutoloader($name, $path);
        $this->_bootstrapModule($name, $path);
    }

    /**
     * Get the module autoloader
     * 
     * @return ModuleAutoloader
     */
    public function getAutoloader()
    {
        if (null === $this->_autoloader) {
            $this->setAutoloader(new ModuleAutoloader());
        }

        return $this->_autoloader;
    }

    /**
     * Set the module autoloader
     *
     * @param  ModuleAutoloader $autoloader
     * @return Modules *Fluent interface*
     */
    public function setAutoloader(ModuleAutoloader $autoloader)
    {
        $this->_autoloader = $autoloader;

        return $this;
    }

    /**
     * Add the given module to the module autoloader
     *
     * @param string $name
     * @param string $path
     */
    protected function _addToAutoloader($name, $path)
    {
        $this->getAutoloader()->addModule($name, $path);
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
        $bootstrap = new $bootstrapClass($this->getBootstrap());
        if (!$bootstrap instanceof \Zend_Application_Bootstrap_Bootstrapper) {
            throw new \LogicException('Module bootstraps must implement Zend_Application_Bootstrap_Bootstrapper');
        }

        $bootstrap->bootstrap();
        $this->_bootstraps[$name] = $bootstrap;
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