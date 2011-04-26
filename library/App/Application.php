<?php

namespace App;

use Symfony\Component\Config\FileLocator,
    Symfony\Component\Yaml\Yaml,
    Silex\Extension,
    Extra\Extensions;

class Application extends \Silex\Application
{
    
    protected $config;
    
    
    public function __construct()
    {
        parent::__construct();
        
        //Load Configuration
        $locator = new FileLocator(CFG_PATH);
        $this->setConfig(Yaml::load($locator->locate('config.yml')));
        
        //Load Extensions
        $this->loadExtensions();
        
        //Add template globals
        $this->defineTemplateGlobals();
    }
    
    private function loadExtensions()
    {
        //Load Twig
        $this->register(new Extension\TwigExtension(), array(
            'twig.path'    => TPL_PATH,
        ));
        
		//Load Doctrine
        require LIB_PATH . 'vendor/Extra/Extensions/DoctrineExtension.php';
        $this->register(new Extensions\DoctrineExtension(), $this->getConfig());
        
        //Load SessionExtension
        $this->register(new Extension\SessionExtension()); 
    }
    
    private function defineTemplateGlobals()
    {
        //Config
        $this->getTwig()->addGlobal('config', $this->getConfig());

    }
    
    /**
     * @param string $action
     * @return App\Action\Base
     */
    public function getAction($action)
    {
        $classname = 'App\Action\\' . $action;
        
        return new $classname($this);
    }
    
    /**
     * @return Twig_Environment
     */
    public function getTwig()
    {
        return $this['twig'];
    }
    
    /**
     * @return array
     */
    public function getConfig($segment = null)     
    {
        
        if ($segment != null && isset($this->config[$segment])){
            return $this->config[$segment];
        }
        
        return $this->config;
    }

	/**
	* @param array
	*/
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Return the Doctrine Entity Manager
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getDoctrineEntityManager()
    {
        return $this['doctrine.orm.em'];
    }
    
    public function getResource($resource){
        return $this[$resource];
    }

}
