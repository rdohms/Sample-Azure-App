<?php

namespace App\Twitter;

class Processor
{
    public function getRTStats($scopeCount, $retweets, $authors)
    {
        
        $data['pct'] = (count($retweets) / $scopeCount) * 100;
        
        $data['word_count'] = $this->getKeywordCount($retweets);
        
        $data['location_count'] = $this->getLocationCounts($authors);
        
        return $data;
    }
    
    private function getKeywordCount($tweets)
    {
        $wordCount = array();
        foreach($tweets as $t)
        {
            
            $words = explode(' ', $t->text);
            foreach($words as $w){
                if (!isset ($wordCount[$w])) $wordCount[$w] = 0;
                $wordCount[$w] += 1;
            }
            
        }
        
        return $wordCount;
    }

    private function getLocationCounts($users)
    {
        $locationCount = array();
        foreach($users as $u)
        {
            if (!isset ($locationCount[$u->location])) $locationCount[$u->location] = 0;
            $locationCount[$u->location] += 1;
        }
        
        return $locationCount;
    }
}