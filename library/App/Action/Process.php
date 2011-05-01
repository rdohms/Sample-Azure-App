<?php

namespace App\Action;

use App\Twitter,
    Doctrine\ORM,
    App\Azure;

class Process extends Base
{
    public function run($args = array())
    {
        $this->monitorQueue();
    }

    /**
     * 
     */
    private function monitorQueue()
    {
        $qManager = new Azure\Queue($this->getApp()->getConfig('azure'));
        $repo = $this->getApp()->getDoctrineEntityManager()->getRepository('App\Entity\User');
        
        while (true){
        
            try{
                $this->logAction("Checking for messages...");
                        
                //Read Queue
                $qMessage = $qManager->getMessage();

                //If no message, sleep for 3 seconds and try again
                if ($qMessage === null){
                    $this->logAction("No pending messages...");
                    sleep(3);
                    continue;
                }

                //Retrieve User
                try {
                    $this->logAction("Pickup message, processing user: ".$qMessage->decoded->twitter_handle);
                    $user = $repo->findByTwitterHandle($qMessage->decoded->twitter_handle);

                } catch (ORM\NoResultException $e) {
                    $this->logAction("User not found in database.");
                    $qManager->deleteMessage($qMessage);
                    continue;
                }

                //Clear Message
                $this->logAction("Removing message form queue...");
                $qManager->deleteMessage($qMessage);

                //Process User
                $this->processUser($user);

                //Warn User
                $this->sendWarning($user);

            } catch (\Exception $e) {
                $this->logAction("Exception found:" .$e->getMessage());
            }
            
            $this->logAction("Initiating sleep for next loop.");
            sleep(2);
        }
        
    }
    
    /**
     * @param App\Entity\User $user
     */
    private function processUser($user)
    {
        $this->logAction("Processing User Stats...");
        $consumer = new Twitter\Consumer($this->getApp()->getConfig('twitter'));
        $consumer->setAccessToken($user->getToken());
        
        $statsMaker = new Twitter\StatsMaker($consumer);
        $stats = $statsMaker->getRTStats();
        
        $user->setStats(serialize($stats));
        
        $this->getApp()->getDoctrineEntityManager()->persist($user);
        $this->getApp()->getDoctrineEntityManager()->flush();
        
    }
    
    /**
     * @param App\Entity\User $user
     */
    private function sendWarning($user)
    {
        $this->logAction("Checking for email and sending warning...");
        if ($user->getEmail() === null) return;
        
        $template = $this->getApp()->getTwig()->loadTemplate('results-ready.email.html.twig');
        $html = $template->render(array(
            'user' => $user,
        ));
        
        
        $mail = new \Zend_Mail();
        $mail->setFrom('sample@sampleapp.com', 'Twitter Sample Application');
        $mail->addTo($user->getEmail(), $user->getTwitterHandle());
        $mail->setBodyHtml($html);
        $mail->send();
        
    }
    
    private function logAction($message)
    {
        echo $message . "\n";
    }
}