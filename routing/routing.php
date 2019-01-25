<?php

use Src\Controllers\RegistrationController;

$registrationController = new RegistrationController();

$request = \Klein\Request::createFromGlobals();
$request->server()->set('REQUEST_URI', substr($_SERVER['REQUEST_URI'],  strlen(APP_PATH)));
$klein = new \Klein\Klein();

$klein->respond('GET', '/registration', [$registrationController, 'getRegistration']);
$klein->respond('POST', '/registration', [$registrationController, 'postRegistration']);

$klein->dispatch($request);
