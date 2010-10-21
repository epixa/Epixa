<?php
/**
 * Epixa Library
 */

namespace Epixa\Service;

use Epixa\Acl\AclManager,
    Epixa\Exception\ConfigException;

/**
 * Abstract service for using a custom or shared acl manager object
 *
 * @category  Epixa
 * @package   Service
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
abstract class AbstractService
{
    /**
     * @var null|AclManager
     */
    protected $aclManager = null;

    /**
     * @var null|AclManager
     */
    protected static $defaultAclManager = null;


    /**
     * Set the default acl manager for all services
     *
     * @param AclManager $aclManager
     */
    public static function setDefaultAclManager(AclManager $aclManager)
    {
        self::$defaultAclManager = $aclManager;
    }

    /**
     * Get the default acl manager for all services
     *
     * @return AclManager
     * @throws ConfigException If no default acl manager is set
     */
    public static function getDefaultAclManager()
    {
        if (self::$defaultAclManager === null) {
            throw new ConfigException('No default acl manager configured');
        }

        return self::$defaultAclManager;
    }

    /**
     * Set the acl manager for this service
     *
     * @param  AclManager $aclManager
     * @return AbstractService *Fluent interface*
     */
    public function setAclManager(AclManager $aclManager)
    {
        $this->aclManager = $aclManager;

        return $this;
    }

    /**
     * Get the acl manager for this service
     *
     * If no acl manager is set, set it to the default acl manager.
     *
     * @return AclManager
     */
    public function getAclManager()
    {
        if ($this->aclManager === null) {
            $this->setAclManager(self::getDefaultAclManager());
        }

        return $this->aclManager;
    }
}