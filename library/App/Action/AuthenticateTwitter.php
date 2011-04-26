<?php

namespace App\Action;

use App\Twitter;

class AuthenticateTwitter extends Base
{
    public function run($args = array())
    {
        $config = $this->getApp()->getConfig('twitter');
        $callBackUrl = 'http://' . $this->getApp()->getResource('request')->server->get('HTTP_HOST') . $config['app']['callbackUrlPath'];
        var_dump($callBackUrl);
        
        //Get Consumer
        $consumer = new Twitter\Consumer($this->getApp()->getConfig('twitter'));
        $consumer->setCallBackUrl($callBackUrl);
        $requestToken = $consumer->getRequestToken();
        
        $this->getApp()->getResource('session')->set('twitter_request_token', serialize($requestToken));
        
        $consumer->redirect();
        
    }

}