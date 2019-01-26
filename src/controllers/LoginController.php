<?php

namespace Src\Controllers;

class LoginController extends BaseController {

  public function getLogin() : void {
    $template = $this->twig->load('login.html');
    echo $template->render();
  }

  public function postLogin() : void {

    $user = $this->preValidateLogin($_POST);

    if($user) {
      $loginAttempt = $this->authenticate($_POST['password'], $user['password']);

      if($loginAttempt) {
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'];
        $this->redirect('greeting');
      }

    $this->invalidLoginAttempt($user, $_POST['password']);
    $template = $this->twig->load('login.html');
    echo $template->render([
      'error' => 'Wrong credentials.'
    ]);
    die;

    }

    $this->invalidLoginAttempt('no-user', $_POST['password']);

    $template = $this->twig->load('login.html');
    echo $template->render([
      'error' => 'No user found.'
    ]);
    die;

  }

  private function preValidateLogin($params) {
    if(empty($params['email'])) {
      $template = $this->twig->load('login.html');
      echo $template->render([
        'error' => 'Please provide email.'
      ]);
      die;
    }

    if(empty($params['password'])) {
      $template = $this->twig->load('login.html');
      echo $template->render([
        'error' => 'Please provide password.'
      ]);
      die;
    }

    $user = $this->getUser($params['email']);

    if($user) return $user;
  }

  private function invalidLoginAttempt($user, $password) {
    $sql =
    "INSERT INTO login_attempts (email, password)
    VALUES (?, ?)";

    $sth = $this->dbConnection->prepare($sql);

    if(is_array($user)) {
      $sth->execute([$user['email'], $password]);
    }

    if($user === 'no-user') {
      $sth->execute(['', $password]);
    }

  }

}
