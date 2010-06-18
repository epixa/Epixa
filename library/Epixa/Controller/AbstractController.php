<?php
/**
 * Epixa Library
 */

namespace Epixa\Controller;

/**
 * Extension of Zend_Controller_Action that changes the defualt path to view
 * scripts.
 *
 * @category  Epixa
 * @package   Controller
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class AbstractController extends \Zend_Controller_Action
{
    /**
     * Initialize View object
     *
     * Initializes {@link $view} if not otherwise a Zend_View_Interface.
     *
     * If {@link $view} is not otherwise set, instantiates a new Zend_View
     * object, using the 'views' subdirectory at the same level as the
     * controller directory for the current module as the base directory.
     * It uses this to set the following:
     * - script path = views/scripts/
     * - helper path = views/helpers/
     * - filter path = views/filters/
     *
     * @return Zend_View_Interface
     * @throws Zend_Controller_Exception if base view directory does not exist
     */
    public function initView()
    {
        if (!$this->getInvokeArg('noViewRenderer') && $this->_helper->hasHelper('viewRenderer')) {
            return $this->view;
        }

        if (isset($this->view) && ($this->view instanceof Zend_View_Interface)) {
            return $this->view;
        }

        $request = $this->getRequest();
        $module  = $request->getModuleName();
        $dirs    = $this->getFrontController()->getControllerDirectory();

        $module = ucwords($module);
        if (empty($module) || !isset($dirs[$module])) {
            $module = $this->getFrontController()->getDispatcher()->getDefaultModule();
        }

        $baseDir = dirname($dirs[$module]) . DIRECTORY_SEPARATOR . 'View';
        if (!file_exists($baseDir) || !is_dir($baseDir)) {
            throw new \Zend_Controller_Exception('Missing base view directory ("' . $baseDir . '")');
        }

        $this->view = new \Zend_View(array('basePath' => $baseDir));

        return $this->view;
    }
}