<?php
/**
 * Epixa Library
 */

namespace Epixa;

require_once 'Zend/Application.php';
require_once 'Zend/Loader/Autoloader.php';

/**
 * Extension of Zend_Application that determines config path by environment
 *
 * @category  Epixa
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Discuss/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class Application extends \Zend_Application
{
    /**
     * {@inheritdoc}
     *
     * Generate the path to the application settings based on the current
     * application environment.
     *
     * @param  string                   $environment
     * @param  string|array|Zend_Config $options Array/Zend_Config of configuration options
     * @throws Zend_Application_Exception When invalid options are provided
     * @return void
     */
    public function __construct($environment, $options = null)
    {
        \Zend_Loader_Autoloader::getInstance()
            ->registerNamespace('Epixa\\')
            ->registerNamespace('Zend_');

        $options = array('config' => APPLICATION_ROOT . '/config/settings/' . APPLICATION_ENV . '.php');
        parent::__construct($environment, $options);
    }
}