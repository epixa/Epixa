<?php
/**
 * Epixa - Example Application
 */

namespace Core\Controller;

use Core\Form\TestForm;

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
}