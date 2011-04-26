<?php

namespace App\Twitter;

class DataMiner
{
    /**
     * @var App\Twitter\Consumer
     */
    protected $consumer;
    
    public function __construct($consumer)
    {
        $this->consumer = $consumer;
    }
    
    public function getLatestTweets($params = array())
    {
        $setParams = array(
            'count' => 200, 
            'trim_user' => true, 
        );
        
        
        $response = $this->consumer->executeHttpRequest(
                        \Zend_Http_Client::GET, 
                        'http://api.twitter.com/1/statuses/user_timeline.json', 
                        array_merge($setParams, $params)
                    );
        
        $tweets = $this->decodeResponse($response);
        
        return $tweets;
    }
    
    public function getLatestReTweetsOfActiveUser()
    {
        $response = $this->consumer->executeHttpRequest(
                        \Zend_Http_Client::GET, 
                        'http://api.twitter.com/1/statuses/retweets_of_me.json', 
                        array('count' => 100)
                    );
        
        $reTweets = $this->decodeResponse($response);
        
        return $reTweets;
    }
    
    public function getAllRetweetsForTweet($id)
    {
        $response = $this->consumer->executeHttpRequest(
                        \Zend_Http_Client::GET, 
                        'http://api.twitter.com/1/statuses/retweets/'.$id.'.json', 
                        array('count' => 100)
                    );
        
        $reTweets = $this->decodeResponse($response);
        
        return $reTweets;
    }
    
    public function getUserInformation($id)
    {
        $response = $this->consumer->executeHttpRequest(
                        \Zend_Http_Client::GET, 
                        'http://api.twitter.com/1/users/show.json', 
                        array('user_id' => $id)
                    );
        
        $userInfo = $this->decodeResponse($response);
        
        return $userInfo;
    }
    
    public function getMultipleUsersInformation($list)
    {
        $response = $this->consumer->executeHttpRequest(
                        \Zend_Http_Client::GET, 
                        'http://api.twitter.com/1/users/lookup.json', 
                        array('user_id' => implode(',',$list))
                    );
        
        $users = $this->decodeResponse($response);
        
        return $users;
    }
    
    protected function decodeResponse($response, $type = 'json')
    {
        switch($type){
            case 'json':
                return json_decode($response->getBody());
            default:
                throw new \Exception('Unsupported encoding format.');
        }
        
    }
}