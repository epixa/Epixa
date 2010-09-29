<?php
/**
 * Epixa Library
 */

namespace Epixa\Auth\Storage;

/**
 * Contract for entity session
 *
 * @category   Epixa
 * @package    Auth
 * @subpackage Storage
 * @copyright  2010 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
interface SessionEntity
{
    /**
     * Get the unique session key
     *
     * @return string
     */
    public function getKey();

    /**
     * Get the user identity associated with this session
     * 
     * @return mixed
     */
    public function getUser();

    /**
     * Update the date of last activity
     */
    public function updateLastActivity();
}