<?php
/**
 * Epixa Library
 */

namespace Epixa\Application\Resource;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration,
    Epixa\Exception\ConfigException;

/**
 * Application resource for configuring Doctrine2
 *
 * @category  Epixa
 * @package   Application
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class Doctrine extends \Zend_Application_Resource_ResourceAbstract
{
    /**
     * Sets up the doctrine configuration, caching, and entity manager
     * 
     * @return EntityManager
     */
    public function init()
    {
        $options = $this->getOptions();

        if (!isset($options['connection'])) {
            throw new ConfigException('No doctrine connection settings set');
        }
        $connection = $options['connection'];
        
        if (!isset($options['proxy'])) {
            throw new ConfigException('No doctrine proxy settings set');
        }

        if (!isset($options['proxy']['directory'])) {
            throw new ConfigException('You must set a proxy directory');
        }
        $proxyDirectory = $options['proxy']['directory'];

        $proxyNamespace = 'Epixa\Proxy';
        if (isset($options['proxy']['namespace'])) {
            $proxyNamespace = $options['proxy']['namespace'];
        }

        $autoGenerateProxies = true;
        if (isset($options['proxy']['autoGenerate'])) {
            $autoGenerateProxies = $options['proxy']['autoGenerate'];
        }

        if (isset($options['cacheClass'])) {
            $cache = new $options['cacheClass'];
        } else {
            $cache = new \Doctrine\Common\Cache\ArrayCache;
        }

        $entityPaths = array();
        if (isset($options['entityPaths'])) {
            $entityPaths = $options['entityPaths'];
        }
        
        $logger = null;
        if (isset($options['loggerClass'])) {
            $logger = new $options['loggerClass'];
        }

        $config = new Configuration;
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);
        $driverImpl = $config->newDefaultAnnotationDriver($entityPaths);
        $config->setMetadataDriverImpl($driverImpl);
        $config->setProxyDir($proxyDirectory);
        $config->setProxyNamespace($proxyNamespace);
        $config->setAutoGenerateProxyClasses($autoGenerateProxies);
        
        if ($logger) {
            $config->setSQLLogger($logger);
        }

        return EntityManager::create($connection, $config);
    }
}