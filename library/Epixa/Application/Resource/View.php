<?php
/**
 * Epixa Library
 */

namespace Epixa\Application\Resource;

use Epixa\View\BaseView,
    Zend_Controller_Action_HelperBroker as HelperBroker;

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
     * In addition, register the default view helper path for the epixa library.
     * 
     * @return BaseView
     */
    public function init()
    {
        $options = $this->getOptions();
        $this->_view = new BaseView($options);

        if (isset($options['doctype'])) {
            $this->_view->doctype()->setDoctype(strtoupper($options['doctype']));
        }

        $this->_view->addHelperPath('Epixa/View/Helper', 'Epixa\\View\\Helper\\');

        $view = parent::init();

        HelperBroker::getExistingHelper('ViewRenderer')
            ->setViewBasePathSpec(':moduleDir/..');
        
        return $view;
    }
}