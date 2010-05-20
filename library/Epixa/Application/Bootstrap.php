<?php
/**
 * Epixa Library
 */

namespace Epixa\Application;

/**
 * Extension of Zend_Application_Bootstrap_Bootstrap that adds Epixa
 * application resource prefix paths to the pluginloader.
 *
 * @category  Epixa
 * @package   Application
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Discuss/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class Bootstrap extends \Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Constructor
     *
     * Ensure FrontController resource is registered
     *
     * @param  Zend_Application|Zend_Application_Bootstrap_Bootstrapper $application
     * @return void
     */
    public function __construct($application)
    {
        $this->getPluginloader()->addPrefixPath(
            'Epixa\\Application\\Resource\\',
            'Epixa/Application/Resource'
        );
        
        parent::__construct($application);
    }
}