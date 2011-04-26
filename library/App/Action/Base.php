<?php

namespace App\Action;

abstract class Base
{
    /**
     * @var Silex\Application 
     */
    protected $app;
    
    public function __construct($app)
    {
        $this->app = $app;
    }
    
    /**
     * @return Silex\Application
     */
    public function getApp()     
    {
        return $this->app;
    }

    abstract function run($args = array());
}
