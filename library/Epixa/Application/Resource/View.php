<?php
/**
 * Epixa Library
 */

namespace Epixa\Application\Resource;

/**
 * Extension of Zend_Application_Resource_View that changes the path spec for
 * module view scripts
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
     * {@inheritdoc}
     * 
     * @return \Zend_View
     */
    public function init()
    {
        $view = parent::init();

        \Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')
            ->setViewBasePathSpec(':moduleDir/View');
        
        return $view;
    }
}