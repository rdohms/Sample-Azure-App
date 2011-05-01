<?php

namespace App\Azure;

class Queue
{
    const QUEUE_MAIN = 'atc-main';
    
    /**
     * @var Zend_Service_WindowsAzure_Storage_Queue
     */
    protected $client;
    
    /**
     * @var Zend_Service_WindowsAzure_Storage_QueueInstance
     */
    protected $queue;
    
    public function __construct($config, $queueName = null)
    {
        $queueName = $queueName ?: self::QUEUE_MAIN;
        
        $qConfig = $this->parseConfig($queueName, $config);
        
        $this->client = new \Zend_Service_WindowsAzure_Storage_Queue(
                $qConfig['host'], $qConfig['accountName'], $qConfig['accountKey']
        );

        $this->queue = $this->getQueueInstance($queueName);
    }
    
    private function getQueueInstance($queueName)
    {
        //Validate Queue Name
        if ( ! $this->client->isValidQueueName($queueName)){
            throw new \Exception("Invalid Queue name:".$queueName);
        }
        
        //Create if not exists
        if ( ! $this->client->queueExists($queueName))
        {
            $this->client->createQueue($queueName);
        }
        
        //Get instance and store
        return $this->client->getQueue($queueName);
    }
    
    private function parseConfig($qName, $config)
    {
        if (!isset ($config['queues'])){ 
            throw new \Exception('Queue Config not set in config file');
        }
        
        if (!isset ($config['queues'][$qName])){ 
            throw new \Exception('Queue Config not set in config file');
        }
        
        return $config['queues'][$qName];
    }
    
    /**
     * @return Zend_Service_WindowsAzure_Storage_Queue 
     */
    public function getClient()     
    {
        return $this->client;
    }

    /**
     * @return Zend_Service_WindowsAzure_Storage_QueueInstance 
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     *
     * @param type $item 
     */
    public function addToQueue($item)
    {
        $this->client->putMessage($this->queue->name, serialize($item));
    }
    
    public function getMessage()
    {
        $messages = $this->client->getMessages($this->queue->name, 1);
        
        if (count($messages) > 0){
            $qMessage = array_shift($messages);
            
            $message = new \stdClass();
            $message->qMessage = $qMessage;
            $message->decoded = unserialize($qMessage->messagetext);
            
            return $message;
        }
        
        return null;
        
    }
    
    public function deleteMessage($message)
    {
        $this->client->deleteMessage($this->queue->name, $message->qMessage);
    }
    
}