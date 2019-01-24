<?php

use Src\Controllers\LoginController;

$loginController = new LoginController();

$request = \Klein\Request::createFromGlobals();
$request->server()->set('REQUEST_URI', substr($_SERVER['REQUEST_URI'],  strlen(APP_PATH)));
$klein = new \Klein\Klein();

$klein->respond('GET', '/admin/login', [$loginController, 'getLogin']);

$klein->dispatch($request);
