<?php

namespace App\Twitter;

class StatsMaker
{
    /**
     * @var App\Twitter\Processor
     */
    protected $processor;
    
    /**
     * @var App\Twitter\DataMiner
     */
    protected $dataMiner;
    
    public function __construct($consumer)
    {
        $this->processor = new Processor();
        $this->dataMiner = new DataMiner($consumer);
        
        if ($consumer->isRateLimitExceeded()){
            throw new \Exception("Rate Limit exceeded, please come back later.");
        }
    }
    
    public function getRTStats()
    {
        
        $latestTweests = $this->dataMiner->getLatestTweets();
        $latestTweestsPage2 = $this->dataMiner->getLatestTweets(array('page' => 2));
        $latestTweestsPage3 = $this->dataMiner->getLatestTweets(array('page' => 3));

        $latestTweests = array_merge($latestTweests, $latestTweestsPage2, $latestTweestsPage3);
        
        $reTweets = array();
        foreach($latestTweests as $tweet){
            if ($tweet->retweet_count == 0) continue;
            $response = $this->dataMiner->getAllRetweetsForTweet($tweet->id);
            foreach($response as $rt){
                $reTweets[] = $rt;
            }
        }
        
        $authorIds = array();
        foreach($reTweets as $reTweet){
            $authorIds[] = $reTweet->user->id_str;
        }
        
        $authors = $this->dataMiner->getMultipleUsersInformation($authorIds);

        $stats = $this->processor->getRTStats(count($latestTweests),$reTweets, $authors);

        return $stats;
        
    }
    
}
