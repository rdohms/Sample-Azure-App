<?php

namespace App\Action;

use App\Twitter;

class Process extends Base
{
    public function run($args = array())
    {
        $consumer = new Twitter\Consumer($this->getApp()->getConfig('twitter'));
        $consumer->setAccessToken(unserialize($this->getApp()->getResource('session')->get('twitter_access_token')));
        
        $statsMaker = new Twitter\StatsMaker($consumer);
        $stats = $statsMaker->getRTStats();
        
        $repo = $this->getApp()->getDoctrineEntityManager()->getRepository('App\Entity\User');
        $user = $repo->findByTwitterHandle($this->getApp()->getResource('session')->get('twitter_handle'));
        $user->setStats(serialize($stats));
        
        $this->getApp()->getDoctrineEntityManager()->persist($user);
        $this->getApp()->getDoctrineEntityManager()->flush();
        
        $template = $this->getApp()->getTwig()->loadTemplate('process.html.twig');

        return $template->render(array());
    }

}