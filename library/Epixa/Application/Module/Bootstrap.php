<?php
/**
 * Epixa Library
 */

namespace Epixa\Application\Module;

use Zend_Application_Module_Bootstrap as BaseModuleBootstrap;

/**
 * Extension of Zend_Application_Module_Bootstrap that does not initiate the
 * resource autoloader
 *
 * @category  Epixa
 * @package   Application
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class Bootstrap extends BaseModuleBootstrap
{
    public $viewHelperPath = null;
    
    /**
     * Constructor
     *
     * @param  Zend_Application|Zend_Application_Bootstrap_Bootstrapper $application
     * @return void
     */
    public function __construct($application)
    {
        $this->setApplication($application);

        $key = strtolower($this->getModuleName());
        if ($application->hasOption($key)) {
            // Don't run via setOptions() to prevent duplicate initialization
            $this->setOptions($application->getOption($key));
        }

        // Use same plugin loader as parent bootstrap
        if ($application instanceof Zend_Application_Bootstrap_ResourceBootstrapper) {
            $this->setPluginLoader($application->getPluginLoader());
        }

        // ZF-6545: ensure front controller resource is loaded
        if (!$this->hasPluginResource('FrontController')) {
            $this->registerPluginResource('FrontController');
        }

        // ZF-6545: prevent recursive registration of modules
        if ($this->hasPluginResource('modules')) {
            $this->unregisterPluginResource('modules');
        }
    }

    /**
     * Initialize the plugin paths for this module
     */
    public function _initPluginPaths()
    {
        if ($this->viewHelperPath) {
            $application = $this->getApplication();
            $view = $application->bootstrap('View')->getResource('View');
            $front = $application->bootstrap('FrontController')->getResource('FrontController');
            
            $moduleName = $this->getModuleName();
            
            $namespace  = $moduleName . '\\View\\Helper\\';
            $helperPath = $front->getModuleDirectory(strtolower($moduleName)) 
                        . DIRECTORY_SEPARATOR . $this->viewHelperPath;
            
            $view->addHelperPath($helperPath, $namespace);
        }
    }

    /**
     * Retrieve module name
     *
     * @return string
     */
    public function getModuleName()
    {
        if (empty($this->_moduleName)) {
            $class = get_class($this);
            if (preg_match('/^([a-z][a-z0-9]*)\\\/i', $class, $matches)) {
                $prefix = $matches[1];
            } else {
                $prefix = $class;
            }
            $this->_moduleName = $prefix;
        }
        return $this->_moduleName;
    }
}