<?php

namespace Src\Controllers;

class LoginController extends BaseController {

  public function getLogin() {
    $template = $this->twig->load('login.html');
    echo $template->render();
  }

  public function postLogin() {
    $sql =
    "SELECT *
    FROM user
    WHERE email=?";

    $sth = $this->dbConnection->prepare($sql);
    $sth->execute(array($_POST['email']));

    $result = $sth->fetch(\PDO::FETCH_ASSOC);

    if(\password_verify($_POST['password'], $result['password'])) {
      $_SESSION['user'] = $result['email'];
      header('Location: http://' . SERVER_NAME . APP_PATH . DIRECTORY_SEPARATOR . 'greeting');
      die;
    }
  }

}
