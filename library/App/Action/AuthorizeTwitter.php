<?php

namespace App\Action;

use App\Twitter,
    App\Entity;

class AuthorizeTwitter extends Base
{
    public function run($args = array())
    {
        //Process Token
        $requestToken = unserialize($this->getApp()->getResource('session')->get('twitter_request_token'));
        
        //Validate Request Token
        if (!$requestToken || !$requestToken->isValid()){
            throw new \Exception('Invalid Twitter Token.');
        }
        
        //Get Access Token
        $consumer = new Twitter\Consumer($this->getApp()->getConfig('twitter'));
        $accessToken = $consumer->getAccessToken($this->getApp()->getResource('request')->query->all(), $requestToken);
        
        //Validate Access Token
        if (!is_object($accessToken) || !$accessToken->isValid()){
            throw new \Exception('Invalid Twitter Access Token.');
        }
        
        //Get User info
        $consumer->setAccessToken($accessToken);
        $response = $consumer->executeHttpRequest(\Zend_Http_Client::GET, 'http://api.twitter.com/1/account/verify_credentials.json', array());
        $twitterInfo = json_decode($response->getBody());

        //Store data in session
        $this->getApp()->getResource('session')->set('twitter_access_token', serialize($accessToken));
        $this->getApp()->getResource('session')->set('twitter_handle',$twitterInfo->screen_name);
        
        //Retrieve Existing user, or create new one
        try {
            $repo = $this->getApp()->getDoctrineEntityManager()->getRepository('App\Entity\User');
            $user = $repo->findByTwitterHandle($twitterInfo->screen_name);
        } catch(Doctrine\ORM\NoResultException $e){
            $user = new Entity\User();
        }
        
        $user->setToken($accessToken);
        $user->setTwitterHandle($twitterInfo->screen_name);

        $this->getApp()->getDoctrineEntityManager()->persist($user);
        $this->getApp()->getDoctrineEntityManager()->flush();
        
        return $this->getApp()->redirect('process');
    }
}