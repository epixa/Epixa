<?php
/**
 * Epixa Library
 */

namespace Epixa\Service;

use Doctrine\ORM\EntityManager,
    Epixa\Exception\ConfigException;

/**
 * Abstract for services that have and/or share a doctrine entity manager
 * 
 * @category  Epixa
 * @package   Service
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
abstract class AbstractDoctrineService extends AbstractService
{
    /**
     * @var null|EntityManager
     */
    protected $entityManager = null;

    /**
     * @var null|EntityManager
     */
    protected static $defaultEntityManager = null;

    
    /**
     * Set the default entity manager for all doctrine services
     * 
     * @param EntityManager $entityManager
     */
    public static function setDefaultEntityManager(EntityManager $entityManager)
    {
        self::$defaultEntityManager = $entityManager;
    }

    /**
     * Get the default entity manager for all doctrine services
     * 
     * @return EntityManager
     * @throws ConfigException If no default entity manager is set
     */
    public static function getDefaultEntityManager()
    {
        if (self::$defaultEntityManager === null) {
            throw new ConfigException('No default entity manager configured');
        }

        return self::$defaultEntityManager;
    }

    /**
     * Set the entity manager for this service
     * 
     * @param  EntityManager $entityManager
     * @return AbstractDoctrineService *Fluent interface*
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        
        return $this;
    }

    /**
     * Get the entity manager for this service
     *
     * If no entity manager is set, set it to the default entity manager.
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if ($this->entityManager === null) {
            $this->setEntityManager(self::getDefaultEntityManager());
        }

        return $this->entityManager;
    }
}
