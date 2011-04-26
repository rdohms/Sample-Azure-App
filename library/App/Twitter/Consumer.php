<?php

namespace App\Twitter;

class Consumer extends \Zend_Oauth_Consumer
{
    
    public function __construct($config)
    {
        $this->config = $config;
        
        parent::__construct(array_merge($config['app'], $config['server']));
    }
    
    public function setCallbackParam( $key, $value )
    {
        
        $url = $this->_config->getCallbackUrl();
        
        if (strpos($url, '?') === false) {
            $url .= '?' . $key . '=' . $value;
        } else {
            $url .= '&' . $key . '=' . $value;
        }
        
        $this->_config->setCallbackUrl($url);
    
    }
    
    public function setCallBackUrl($url)
    {
        $this->_config->setCallbackUrl($url);
    }
    
    /**
     * @param $accessToken the $accessToken to set
     */
    public function setAccessToken( $accessToken )
    {
        $this->_accessToken = $accessToken;
    }
    
    public function executeHttpRequest($method, $uri, $params = array())
    {
        $token = $this->getToken();
        
        if (!is_object($token) || !$token->isValid()){
            throw new \Exception("Invalid Twitter token");
        }
        
        $client = $token->getHttpClient(array_merge($this->config['app'], $this->config['server']));
        $client->setMethod($method);
        $client->setUri($uri);
      
        $paramMethod = "setParameter".ucfirst(strtolower($method));
        foreach($params as $key => $value){
            $client->$paramMethod($key, $value);
        }
        
        $response = $client->request();
        
        return $response;
        
    }
    
    public function getRateLimit()
    {
        
        $response = $this->executeHttpRequest(\Zend_Http_Client::GET, 'http://api.twitter.com/1/account/rate_limit_status.json');
        $decodedBody = json_decode($response->getBody());
        
        return $decodedBody;
    }
    
    public function isRateLimitExceeded()
    {
        $status = $this->getRateLimit();
        
        return ($status->remaining_hits <= 0);
    }
}
