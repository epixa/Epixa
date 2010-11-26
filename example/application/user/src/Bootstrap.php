<?php
/**
 * Epixa - Example Application
 */

namespace User;

use Epixa\Application\Module\Bootstrap as ModuleBootstrap;

/**
 * Bootstrap the user module
 *
 * @category  Bootstrap
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class Bootstrap extends ModuleBootstrap
{
    protected $viewHelperPath = 'View/Helper';
}