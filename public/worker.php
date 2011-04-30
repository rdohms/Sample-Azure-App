<?php

use Symfony\Component\HttpFoundation\Request;

//Get App structure and run!
$app = require '../library/app.php';

//Forge Request to Worker
$request = Request::create('/process');

//Run worker
$app->handle($request);

