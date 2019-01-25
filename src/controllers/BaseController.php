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

    $this->dbConnection = new \PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PW);
  }

}
