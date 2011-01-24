<?php
/**
 * Epixa - Example Application
 */

namespace Core\Controller;

use Core\Form\TestForm,
    LogicException,
    Exception;

/**
 * Default controller
 *
 * @category   Module
 * @package    Core
 * @subpackage Controller
 * @copyright  2010 epixa.com - Court Ewing
 * @license    http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author     Court Ewing (court@epixa.com)
 */
class IndexController extends \Epixa\Controller\AbstractController
{
    public function indexAction()
    {
        $request = $this->getRequest();
        
        $form = new TestForm();

        if (!$request->isPost() || !$form->isValid($request->getPost())) {
            $this->view->form = $form;
            return;
        }

        var_dump($form->getValues());
        die('<p>Core\Controller\IndexController::indexAction</p>');
    }
    
    public function assertAction()
    {
        try {
            $this->_helper->assert->isset(null);
        } catch (Exception $e) {
            echo '<p>Default assertion failure</p>';
        }
        
        try {
            $this->_helper->assert->isset(null, 'LogicException');
        } catch (LogicException $e) {
            echo '<p>LogicException assertion failure</p>';
        }
        
        try {
            $this->_helper->assert->isset(null, 'LogicException', 'Test Message');
        } catch (LogicException $e) {
            printf(
                '<p>LogicException assertion failure with message: %s   </p>', 
                $e->getMessage()
            );
        }
        
        die('<p>Core\Controller\IndexController::assertAction</p>');
    }
}