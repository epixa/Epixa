<?php
/**
 * Epixa Library
 */

namespace Epixa\Acl;

use Zend_Acl as Acl,
    Zend_Acl_Resource_Interface as ResourceInterface,
    Epixa\Acl\AclService,
    Epixa\Acl\MultiRoles,
    InvalidArgumentException;

/**
 * @category  Epixa
 * @package   Acl
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class AclManager
{
    /**
     * @var Acl
     */
    protected $_acl;

    /**
     * @var AclService
     */
    protected $_aclService;


    /**
     * Constructor
     *
     * Set the acl and acl service objects
     * 
     * @param Acl        $acl
     * @param AclService $aclService
     */
    public function __construct(Acl $acl, AclService $aclService)
    {
        $this->setAcl($acl)
             ->setAclService($aclService);
    }

    /**
     * Register a new resource on the acl
     * 
     * @param  string $resource
     * @return AclManager *Fluent interface*
     */
    public function registerResource($resource)
    {
        $this->getAcl()->addResource($resource);

        return $this;
    }

    /**
     * Register multiple resources on the acl
     * 
     * @param  array $resources
     * @return AclManager *Fluent interface*
     */
    public function registerResources(array $resources)
    {
        foreach ($resources as $resource) {
            $this->registerResource($resource);
        }

        return $this;
    }

    /**
     * Is the specified role allowed to access the given resource
     * 
     * @param  MultiRoles|RoleInterface|string $identity
     * @param  string|ResourceInterface        $resource
     * @param  null|string                     $privilege
     * @return boolean
     */
    public function isAllowed($identity, $resource, $privilege = null)
    {
        $acl = $this->getAcl();

        if ($resource instanceof ResourceInterface) {
            $resource = $resource->getResourceId();
        } else if (!is_string($resource)) {
            throw new InvalidArgumentException('Resource must be a string or instance of Zend_Acl_Resource_Interface');
        }

        // if the resource isn't in the acl, then we should add it to the acl
        // and then get all of the roles and rules for it
        if (!$acl->has($resource)) {
            $this->getAclService()->loadResourceRules($acl, $resource);
        }

        $roles = $this->getIdentityRoles($identity);

        // if any of the roles is allowed to access that resource, then ok
        foreach ($roles as $role) {
            if (!$acl->hasRole($role)) {
                continue;
            }

            if ($acl->isAllowed($role, $resource, $privilege)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the roles associated with the given identity
     * 
     * @param  MultiRoles|RoleInterface|string $identity
     * @return array
     * @throws InvalidArgumentException If invalid identity is provided
     */
    public function getIdentityRoles($identity)
    {
        $roles = array();
        if ($identity instanceof MultiRoles) {
            foreach ($identity->getRoles() as $role) {
                array_push($roles, $role);
            }
        } else if ($identity instanceof RoleInterface || is_string($identity)) {
            array_push($roles, $identity);
        } else {
            throw new InvalidArgumentException('Invalid model specified');
        }

        return $roles;
    }

    /**
     * Get the acl instance
     *
     * @return Acl
     */
    public function getAcl()
    {
        return $this->_acl;
    }

    /**
     * Set the acl instance
     *
     * @param  Acl $acl
     * @return AclManager *Fluent interface*
     */
    public function setAcl(Acl $acl)
    {
        $this->_acl = $acl;

        return $this;
    }

    /**
     * Get the acl service instance
     *
     * @return AclService
     */
    public function getAclService()
    {
        return $this->_aclService;
    }

    /**
     * Get the acl service instance
     *
     * @param  AclService $service
     * @return AclManager
     */
    public function setAclService(AclService $service)
    {
        $this->_aclService = $service;

        return $this;
    }
}