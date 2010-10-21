<?php
/**
 * Epixa Library
 */

namespace Epixa\Acl;

/**
 * Contract for an object that can return multiple roles
 *
 * @category  Epixa
 * @package   Acl
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
interface MultiRoles
{
    /**
     * Get the roles of the current object
     *
     * @return array
     */
    public function getRoles();
}