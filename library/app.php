<?php

require 'bootstrap.php';

$app = new App\Application();

//Index
$app->get('/', function () use ($app) {
   $action = $app->getAction('Index');
   return $action->run();
});

$app->get('/authenticate-twitter', function () use ($app){
    $action = $app->getAction('AuthenticateTwitter');
    return $action->run();
});

$app->get('/authorize-twitter', function () use ($app){
    $action = $app->getAction('AuthorizeTwitter');
    return $action->run();
});

$app->get('/registration-done/{handle}', function ($handle) use ($app){
    $action = $app->getAction('RegistrationDone');
    return $action->run(array('handle' => $handle));
});

$app->post('/save-email', function () use ($app){
    $action = $app->getAction('SaveEmail');
    return $action->run();
});

$app->get('/process', function () use ($app){
    $action = $app->getAction('Process');
    return $action->run();
});

$app->get('/result/{handle}', function ($handle) use ($app){
    $action = $app->getAction('Result');
    return $action->run(array('handle' => $handle));
});

$app->get('/updateschema', function () use ($app){

    $em = $app->getDoctrineEntityManager();
    
    $metadatas = $em->getMetadataFactory()->getAllMetadata();
    $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
    $tool->updateSchema($metadatas, true);
    
});


return $app;