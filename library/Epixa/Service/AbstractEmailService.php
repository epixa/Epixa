<?php
/**
 * Epixa Library
 */

namespace Epixa\Service;

use Zend_Mail as Mailer,
    Zend_View_Interface as View,
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
     * Sets the default view object for email services
     *
     * @param View $view
     */
    public static function setDefaultView(View $view)
    {
        self::$defaultView = $view;
    }

    /**
     * Gets the default view object for email services
     *
     * @return View
     * @throws ConfigException If no default view is set
     */
    public static function getDefaultView()
    {
        if (self::$defaultView === null) {
            throw new ConfigException('No default view configured');
        }

        return self::$defaultView;
    }
    
    /**
     * Sets the mailer for this service
     *
     * @param  Mailer $mailer
     * @return AbstractEmailService *Fluent interface*
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

    /**
     * Sets the view for this service
     *
     * @param  View $view
     * @return AbstractEmailService *Fluent interface*
     */
    public function setView(View $view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Gets the view for this service
     *
     * If no view is set, sets it to the default view.
     *
     * @return View
     */
    public function getView()
    {
        if ($this->view === null) {
            $this->setView(self::getDefaultView());
        }

        return $this->view;
    }
    
    /**
     * Renders the given email template and sends it to the given email
     * 
     * @param string               $template
     * @param string               $email
     * @param string               $name
     * @param string               $subject
     * @param null|\Zend_Mime_Part $attachment
     */
    public function send($template, $email, $name, $subject, $attachment = null)
    {
        $view = $this->getView();
        
        $html = $view->render($this->_getHtmlTemplateFile($template));
        $text = $view->render($this->_getTextTemplateFile($template));
        
        $mail = $this->getMailer();
        $mail->addTo($email, $name)
             ->setSubject($subject)
             ->setBodyHtml($html)
             ->setBodyText($text);
        
        if ($attachment !== null) {
            $mail->addAttachment($attachment);
        }
        
        $mail->send();
    }
    
    
    /**
     * Creates the path to the html version of the given template file
     * 
     * @param  string $template
     * @return string
     */
    protected function _getHtmlTemplateFile($template)
    {
        return $template . '-html.phtml';
    }
    
    /**
     * Creates the path to the text version of the given template file
     * 
     * @param  string $template
     * @return string
     */
    protected function _getTextTemplateFile($template)
    {
        return $template . '-text.phtml';
    }
}