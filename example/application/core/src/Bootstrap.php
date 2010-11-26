<?php
/**
 * Epixa - Example Application
 */

namespace Core;

use Epixa\Application\Module\Bootstrap as ModuleBootstrap;

/**
 * Bootstrap the core module
 *
 * @category  Bootstrap
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class Bootstrap extends ModuleBootstrap
{
    public $viewHelperPath = 'View/Helper';
}