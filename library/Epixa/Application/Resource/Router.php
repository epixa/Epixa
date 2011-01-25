<?php
/**
 * Epixa Library
 */

namespace Epixa\Application\Resource;

use Zend_Application_Resource_Router as RouterResource,
    Zend_Controller_Router_Rewrite as RewriteRouter;

/**
 * Extension of Zend_Application_Resource_Router that adds the ability to load 
 * routes from a file
 *
 * @category  Epixa
 * @package   Application
 * @copyright 2011 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class Router extends RouterResource
{
    /**
     * {@inheritdoc}
     *
     * In addition, load routes from file if a file was specified
     * 
     * @return Router
     */
    public function init()
    {
        $routes = array();
        foreach ($this->getOptions() as $key => $value) {
            if (strtolower($key) == 'file') {
                $routes = include $value;
                break;
            }
        }
        
        $this->setOptions(array('routes' => $routes));
        
        return parent::init();
    }
}