<?php

use Src\Controllers\GreetingController;
use Src\Controllers\LoginController;
use Src\Controllers\LogoutController;
use Src\Controllers\RegistrationController;

$registrationController = new RegistrationController();
$loginController = new LoginController();
$logoutController = new LogoutController();
$greetingController = new GreetingController();

$request = \Klein\Request::createFromGlobals();
$request->server()->set('REQUEST_URI', substr($_SERVER['REQUEST_URI'], strlen(APP_PATH)));
$klein = new \Klein\Klein();

$klein->respond('GET', '/registration-token/[:token]', [$registrationController, 'activateUser']);

$klein->respond('GET', '/logout', [$logoutController, 'getLogout']);

// If the user is not logged in then it cannot visit the protected pages.
$klein->with('/admin', function () use ($klein) {
    $klein->respond(function ($request, $response) {
        if (!isset($_SESSION['email'])) {
            $response->redirect(APP_PATH . '/login')->send();
        }
    });
});

$klein->respond('GET', '/admin/greeting', [$greetingController, 'getGreeting']);

// If the user is logged in then it cannot visit the login and registration pages.
$klein->with('/?', function () use ($klein) {
    $klein->respond(function ($request, $response) {
        if (isset($_SESSION['email'])) {
            $response->redirect(APP_PATH . '/admin/greeting')->send();
        }
    });
});

$klein->respond('GET', '/registration', [$registrationController, 'getRegistration']);
$klein->respond('POST', '/registration', [$registrationController, 'postRegistration']);

$klein->respond('GET', '/login', [$loginController, 'getLogin']);
$klein->respond('POST', '/login', [$loginController, 'postLogin']);

$klein->respond(function ($request, $response, $service, $app) {
    $response->redirect(APP_PATH . '/login');
});

$klein->dispatch($request);
