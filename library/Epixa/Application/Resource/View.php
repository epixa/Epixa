<?php
/**
 * Epixa Library
 */

namespace Epixa\Application\Resource;

/**
 * Extension of Zend_Application_Resource_View that does not instantiate
 * viewRenderer
 *
 * @category  Epixa
 * @package   Application
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class View extends \Zend_Application_Resource_View
{
    /**
     * Return the configured view object
     *
     * The difference between this and the parent::init is that this does not
     * instantiate a viewRenderer object since Epixa specifically disables it.
     * 
     * @return \Zend_View
     */
    public function init()
    {
        return $this->getView();
    }
}