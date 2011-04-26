<?php
use Symfony\Component\ClassLoader\UniversalClassLoader,
    Symfony\Component\Config,
    Symfony\Component\Yaml\Yaml; 

//Define PATHS
define('ROOT_PATH', __DIR__ . '/../');
define('LIB_PATH', ROOT_PATH . 'library/');
define('TPL_PATH', ROOT_PATH . 'templates/');
define('CFG_PATH', ROOT_PATH . 'config/');

//Load Silex
require LIB_PATH . 'vendor/silex.phar';

set_include_path( get_include_path() . PATH_SEPARATOR . LIB_PATH . 'vendor/' );

//Get Namespace Autoloader
$loader = new UniversalClassLoader(); 
$loader->registerNamespace('App', LIB_PATH);
$loader->registerNamespace('Symfony', LIB_PATH . 'vendor');
$loader->registerNamespace('Extra', LIB_PATH . 'vendor/Extra/Extensions/');
$loader->registerNamespace('Doctrine\Common', LIB_PATH . 'vendor/Doctrine/doctrine-common/lib');
$loader->registerNamespace('Doctrine\DBAL', LIB_PATH . 'vendor/Doctrine/doctrine-dbal/lib');
$loader->registerNamespace('Doctrine\ORM', LIB_PATH . 'vendor/Doctrine/doctrine/lib');
$loader->registerPrefix( 'Twig_' , LIB_PATH . 'vendor/Twig/lib/'); 
$loader->registerPrefix( 'Zend_' , LIB_PATH . 'vendor/Zend/'); 
$loader->registerPrefixFallback(explode(PATH_SEPARATOR, get_include_path()));
$loader->register(); 
