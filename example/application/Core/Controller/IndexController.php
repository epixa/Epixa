<?php
/**
 * Epixa - Example Application
 */

namespace Core\Controller;

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
        $this->render('index');
    }
}