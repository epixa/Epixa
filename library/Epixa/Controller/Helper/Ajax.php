<?php
/**
 * Epixa Library
 */

namespace Epixa\Controller\Helper;

use Zend_Controller_Action_Helper_Abstract as AbstractHelper,
    Zend_Controller_Action_HelperBroker as HelperBroker,
    Zend_Form as BaseForm,
    Zend_Json as JsonEncoder,
    Exception;
    

/**
 * Controller helper that allows for various forms of assertion functionality
 *
 * @category   Epixa
 * @package    Controller
 * @subpackage Helper
 * @copyright  2011 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Ajax extends AbstractHelper
{
    const SUCCESS = 'success';
    const ERROR   = 'error';
    const INVALID = 'invalid';
    const DENIED  = 'denied';
    
    /**
     * If this is an ajax request, disable automatic rendering and the layout
     */
    public function init()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_getHelper('viewRenderer')->setNoRender(true);
            $this->_getHelper('layout')->disableLayout(true);
        }
    }
    
    /**
     * Sends a json response indicating that the request was successful.
     * 
     * @param array $data
     */
    public function success(array $data = array())
    {
        $this->send(array(
            'status' => self::SUCCESS,
            'data' => $data
        ));
    }
    
    /**
     * Sends a json response indicating that a fatal error has occurred.
     * 
     * @param Exception $e
     */
    public function error(Exception $e)
    {
        $this->send(array(
            'status' => self::ERROR,
            'message' => $e->getMessage()
        ));
    }
    
    /**
     * Sends a json response indicating the data submitted to the given form was 
     * invalid.
     * 
     * The error messages are also returned.
     * 
     * @param BaseForm $form
     */
    public function invalid(BaseForm $form)
    {
        $this->send(array(
            'status' => self::INVALID,
            'errors' => $form->getErrorMessages()
        ));
    }
    
    /**
     * Sends a json response indicating the user does not have permission to 
     * perform to current request
     */
    public function denied()
    {
        $this->send(array(
            'status' => self::DENIED
        ));
    }
    
    
    /**
     * Send the data as a json response
     * 
     * @param array $data
     */
    public function send(array $data)
    {
        $data = JsonEncoder::encode($data);

        $response = $this->getResponse();
        $response->setHeader('Content-Type', 'application/json', true);
        
        echo $data;
    }
    
    /**
     * Get a specific action helper
     * 
     * @param  string $name
     * @return AbstractHelper
     */
    protected function _getHelper($name)
    {
        $controller = $this->getActionController();
        $helper = $controller 
                ? $controller->_helper->$name
                : HelperBroker::getStaticHelper($name);
        
        return $helper;
    }
}