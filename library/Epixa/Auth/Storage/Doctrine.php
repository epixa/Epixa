<?php
/**
 * Epixa Library
 */

namespace Epixa\Auth\Storage;

use Zend_Auth_Storage_Session as SessionStorage,
    Doctrine\ORM\EntityManager,
    Epixa\Exception\ConfigException,
    InvalidArgumentException;

/**
 * Zend_Auth storage adapter that uses doctrine to read/write identity
 * information.
 *
 * State between requests is tracked by a unique session key that is stored in
 * php session.
 *
 * @category   Epixa
 * @package    Auth
 * @subpackage Storage
 * @copyright  2010 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Doctrine extends SessionStorage
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var null|SessionEntity
     */
    protected $session = null;

    /**
     * @var string
     */
    protected $keyProperty = 'key';


    /**
     * Constructor
     *
     * Set the doctrine entity manager, the name of the entity class, and
     * optionally the name of the entity key property.
     *
     * @param EntityManager $em
     * @param string        $entityClass
     * @param null|string   $keyProperty
     */
    public function __construct(EntityManager $em, $entityClass, $keyProperty = null)
    {
        parent::__construct();

        $this->setEntityManager($em)
             ->setEntityClass($entityClass);

        if (null !== $keyProperty) {
            $this->setKeyProperty($keyProperty);
        }
    }

    /**
     * Returns true if and only if storage is empty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        if (parent::isEmpty() || null === $this->read()) {
            return true;
        }

        return false;
    }

    /**
     * Return the user identity associated with the current session or null if
     * no user is authenticated.
     *
     * @return mixed
     */
    public function read()
    {
        $sessionKey = parent::read();

        if (isset($sessionKey) && null === $this->session) {
            $className = $this->getEntityClass();
            $keyName   = $this->getKeyProperty();

            $em   = $this->getEntityManager();
            $repo = $em->getRepository($className);
            $session = $repo->findOneBy(array($keyName => $sessionKey));

            // if no matching session exists in the database, then clear it out
            if (null === $session) {
                parent::clear();
            } else {
                $session->updateLastActivity();
                $em->flush();
            }
            
            $this->session = $session;
        }

        return $this->session ? $this->session->getUser() : null;
    }

    /**
     * Persists the entity in the database and writes the key to the session
     *
     * @param  SessionEntity $session
     * @throws InvalidArgumentException If invalid SessionEntity is passed
     */
    public function write($session)
    {
        $entityClass = $this->getEntityClass();
        if (!$session instanceof $entityClass) {
            throw new InvalidArgumentException(sprintf(
                'Expecting type `%s`, but got `%s`',
                $entityClass,
                is_object($session) ? get_class($session) : gettype($session)
            ));
        }

        $em = $this->getEntityManager();

        $em->persist($session);
        $em->flush();

        $this->session = $session;

        parent::write($session->getKey());
    }

    /**
     * Clears contents from local and persistent storage
     */
    public function clear()
    {
        if (null !== $this->read()) {
            $em = $this->getEntityManager();
            $em->remove($this->session);
            $em->flush();
            $this->session = null;
        }

        parent::clear();
    }

    /**
     * Set the doctrine entity manager
     *
     * @param  EntityManager $entityManager
     * @return Doctrine *Fluent interface*
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * Set the name of the session entity class
     * 
     * @param  string $name
     * @return Doctrine *Fluent interface*
     */
    public function setEntityClass($name)
    {
        $this->entityClass = (string)$name;
        
        return $this;
    }

    /**
     * Set the name of the entity key property
     * 
     * @param  string $name
     * @return Doctrine *Fluent interface*
     */
    public function setKeyProperty($name)
    {
        $this->keyProperty = (string)$name;

        return $this;
    }

    /**
     * Get the name of the entity key property
     * 
     * @return string
     */
    public function getKeyProperty()
    {
        return $this->keyProperty;
    }

    /**
     * Get the name of the session entity class
     * 
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Get the doctrine entity manager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}