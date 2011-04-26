<?php

namespace App\Action;

class Result extends Base
{
    public function run($args = array())
    {
        
        $repo = $this->getApp()->getDoctrineEntityManager()->getRepository('App\Entity\User');
        $user = $repo->findByTwitterHandle($this->getApp()->getResource('session')->get('twitter_handle'));

        $stats = unserialize($user->getStats());
        
        
        $locationGraphUrl = $this->buildUrl($stats['location_count'], 'Retweets by Location');
        
        //Trim word graph
        arsort($stats['word_count']);
        $stats['word_count'] = array_slice($stats['word_count'], 0, 15);
        
        $wordGraphUrl = $this->buildUrl($stats['word_count'], 'Word count in retweet');
        
        $template = $this->getApp()->getTwig()->loadTemplate('result.html.twig');
        return $template->render(array(
            'user' => $user,
            'locationGraphUrl' => $this->convertUrl($locationGraphUrl),
            'wordGraphUrl' => $this->convertUrl($wordGraphUrl)
        ));
    }

    
    private function convertUrl($url)
    {
        $url = str_replace('\n', '', $url);
        $url = str_replace('&', '&amp;', $url);
        $url = str_replace(' ', '%20', $url);
        
        return $url;
    }
    
    private function buildUrl($data, $title, $widthHeight = '600x420')
    {
        
        $url  = 'http://chart.apis.google.com/chart?';
        $url .= '&cht=bhs';
        $url .= '&chxl=0:|'.implode('|',array_keys($data));
        $url .= '&chxt=y,x';
        $url .= '&chxr=0,5,100|1,0,'.max($data);
        $url .= '&chbh=a,6';
        $url .= '&chs='.$widthHeight;
        $url .= '&chds=0,'.max($data);
        $url .= '&chd=t:'.implode(',',$data);
        $url .= '&chtt='.  urlencode($title);
        
        return $url;
    }
}