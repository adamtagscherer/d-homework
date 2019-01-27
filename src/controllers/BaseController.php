<?php

namespace Src\Controllers;

use Src\Models\LoginAttemptTrait;
use Src\Models\RegistrationTokenTrait;
use Src\Models\UserTrait;

class BaseController
{

    protected $twig;
    protected $dbConnection;
    use UserTrait, LoginAttemptTrait, RegistrationTokenTrait;

    /**
     * Bootstrap twig and database connection.
     */
    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'views');
        $this->twig = new \Twig_Environment($loader, [
            'cache' => dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'views/cache',
        ]);

        if (isset($_SESSION['name'])) {
            $this->twig->addGlobal('name', $_SESSION['name']);
        }

        if (isset($_SESSION['email'])) {
            $this->twig->addGlobal('email', $_SESSION['email']);
        }

        $this->twig->addGlobal('baseUrl', SERVER_NAME . APP_PATH);

        $this->dbConnection = new \PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PW);
    }

    /**
     * Redirects to the given route.
     *
     * @param string $route
     * @return void
     */
    protected function redirect(string $route)
    {
        header('Location: ' . SERVER_NAME . APP_PATH . DIRECTORY_SEPARATOR . $route);
        die;
    }

    /**
     * Renders the given template.
     *
     * @param string $template
     * @param array $parameters
     * @return void
     */
    protected function render(string $template, array $parameters)
    {
        $template = $this->twig->load($template . '.html');
        echo $template->render($parameters);
        die;
    }

    /**
     * Checks if the given password matches the password in the database.
     *
     * @param string $userPassword
     * @param string $paramPassword
     * @return void
     */
    public function authenticate(string $userPassword, string $paramPassword)
    {
        return \password_verify($userPassword, $paramPassword);
    }

}
