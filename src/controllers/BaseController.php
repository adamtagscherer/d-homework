<?php

namespace Src\Controllers;

class BaseController {

  protected $twig;
  protected $dbConnection;

  function __construct() {
    $loader = new \Twig_Loader_Filesystem(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'views');
    $this->twig = new \Twig_Environment($loader, [
      // 'cache' => dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'views/cache'
    ]);

    if(isset($_SESSION['name'])) $this->twig->addGlobal('name', $_SESSION['name']);
    if(isset($_SESSION['email'])) $this->twig->addGlobal('email', $_SESSION['email']);
    $this->twig->addGlobal('baseUrl', SERVER_NAME . APP_PATH);

    $this->dbConnection = new \PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PW);
  }

  protected function redirect(String $route) {
    header('Location: ' . SERVER_NAME . APP_PATH . DIRECTORY_SEPARATOR . $route);
    die;
  }

  protected function fetchUser($email) {
    $sql =
    "SELECT *
    FROM user
    WHERE email=?";

    $sth = $this->dbConnection->prepare($sql);
    $sth->execute([$email ?? $_SESSION['email']]);

    return $sth->fetch(\PDO::FETCH_ASSOC);
  }

  public function authenticate(string $userPassword, string $paramPassword) {
    return \password_verify($userPassword, $paramPassword);
  }

}
