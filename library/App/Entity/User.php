<?php

namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Entity\Repository\UserRepository")
 * @Table(name="users")
 */
class User
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     * @var integer
     */
    protected $id;
    
    /**
     * @Column(type="text")
     * @var string
     */
    protected $token;
    
    /**
     * @Column(type="string", length=150)
     * @var string
     */
    protected $twitterHandle;
    
    /**
     * @Column(type="text", nullable=true)
     * @var string
     */
    protected $stats;
    
    public function getId()     
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getToken()     
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getTwitterHandle()
    {
        return $this->twitterHandle;
    }

    public function setTwitterHandle($twitterHandle)
    {
        $this->twitterHandle = $twitterHandle;
    }

    public function getStats()     
    {
        return $this->stats;
    }

    public function setStats($stats)
    {
        $this->stats = $stats;
    }


    
}