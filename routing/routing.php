<?php

use Src\Controllers\RegistrationController;
use Src\Controllers\LoginController;
use Src\Controllers\LogoutController;
use Src\Controllers\GreetingController;

$registrationController = new RegistrationController();
$loginController = new LoginController();
$logoutController = new LogoutController();
$greetingController = new GreetingController();

$request = \Klein\Request::createFromGlobals();
$request->server()->set('REQUEST_URI', substr($_SERVER['REQUEST_URI'], strlen(APP_PATH)));
$klein = new \Klein\Klein();

$klein->respond('GET', '/registration', [$registrationController, 'getRegistration']);
$klein->respond('POST', '/registration', [$registrationController, 'postRegistration']);

$klein->respond('GET', '/login', [$loginController, 'getLogin']);
$klein->respond('POST', '/login', [$loginController, 'postLogin']);

$klein->respond('GET', '/logout', [$logoutController, 'getLogout']);

$klein->respond('GET', '/greeting', [$greetingController, 'getGreeting']);

$klein->dispatch($request);
