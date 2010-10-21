<?php
/**
 * Epixa Library
 */

namespace Epixa\Acl;

use Zend_Acl as Acl;

/**
 * Contract for a service that pulls and pushes information to a persistent acl 
 * storage
 *
 * @category  Epixa
 * @package   Acl
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
interface AclService
{
    /**
     * Load the specific resource information and related rules into the acl
     *
     * @param  Acl    $acl
     * @param  string $resource
     */
    public function loadResourceRules(Acl $acl, $resource);
}