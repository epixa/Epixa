<?php
/**
 * Epixa Library
 */

namespace Epixa\Service\Twitter;

use Zend_Service_Twitter as TwitterService,
    Zend_Rest_Client_Result as Result,
    Zend_Http_Client_Exception as HttpClientException;

/**
 * Some changes to Zend Framework's Twitter integration to bring the api more up
 * to date
 * 
 * @category  Epixa
 * @package   Service
 * @copyright 2011 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class RestApi extends TwitterService
{
    /**
     * {@inheritdoc}
     *
     * @param  array $params The various parameters for this query
     * @return Result
     */
    public function userFriends(array $params = array())
    {
        $this->_init();
        $path = '/1/statuses/friends';
        $_params = array();

        foreach ($params as $key => $value) {
            switch (strtolower($key)) {
                case 'id':
                    $_params['user_id'] = (int) $value;
                    break;
                case 'screen_name':
                    $_params['screen_name'] = (string) $value;
                    break;
                case 'cursor':
                    $_params['cursor'] = (int) $value;
                    break;
                default:
                    break;
            }
        }
        $path .= '.xml';

        $response = $this->_get($path, $_params);
        return new Result($response->getBody());
    }
}
