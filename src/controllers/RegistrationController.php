<?php

namespace Src\Controllers;

class RegistrationController extends BaseController {

  public function getRegistration() {
    $template = $this->twig->load('registration.html');
    echo $template->render();
  }

  public function postRegistration() {

    if(!$this->validString($_POST['password'])) {
      $template = $this->twig->load('registration.html');
      echo $template->render([
        'error' => 'The password must be at least 6 characters long and contain small letter, capital letter, digit and special character.'
      ]);
      die;
    }

    $sql =
    "INSERT INTO user (email, name, password)
    VALUES (?, ?, ?)";

    $sth = $this->dbConnection->prepare($sql);
    $sth->execute(array($_POST['email'], $_POST['name'], \password_hash($_POST['password'], PASSWORD_DEFAULT)));

    // mail("kidama62@gmail.com", "My subject", "pog");

    $this->getRegistration();
  }

  function validString($string) {
    $containsSmallLetter = preg_match('/[a-z]/', $string);
    $containsCapsLetter = preg_match('/[A-Z]/', $string);
    $containsDigit = preg_match('/\d/', $string);
    $containsSpecial = preg_match('/[^a-zA-Z\d]/', $string);
    $length = strlen($string) >= 6;
    return ($containsSmallLetter && $containsCapsLetter && $containsDigit && $containsSpecial && $length);
  }

}
