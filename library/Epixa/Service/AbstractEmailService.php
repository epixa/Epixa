<?php
/**
 * Epixa Library
 */

namespace Epixa\Service;

use Zend_Mail as Mailer,
    Epixa\Exception\ConfigException;

/**
 * Abstract service for accessing an emailer manager
 *
 * @category  Epixa
 * @package   Service
 * @copyright 2011 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
abstract class AbstractEmailService
{
    /**
     * @var null|Mailer
     */
    protected $mailer = null;

    /**
     * @var null|Mailer
     */
    protected static $defaultMailer = null;


    /**
     * Sets the default mailer for all services
     *
     * @param Mailer $mailer
     */
    public static function setDefaultMailer(Mailer $mailer)
    {
        self::$defaultMailer = $mailer;
    }

    /**
     * Gets the default mailer for all services
     *
     * @return Mailer
     * @throws ConfigException If no default mailer is set
     */
    public static function getDefaultMailer()
    {
        if (self::$defaultMailer === null) {
            throw new ConfigException('No default mailer configured');
        }

        return self::$defaultMailer;
    }

    /**
     * Sets the mailer for this service
     *
     * @param  Mailer $mailer
     * @return AbstractService *Fluent interface*
     */
    public function setMailer(Mailer $mailer)
    {
        $this->mailer = $mailer;

        return $this;
    }

    /**
     * Gets the mailer for this service
     *
     * If no mailer is set, sets it to the default mailer.
     *
     * @return Mailer
     */
    public function getMailer()
    {
        if ($this->mailer === null) {
            $this->setMailer(self::getDefaultMailer());
        }

        return $this->mailer;
    }
}