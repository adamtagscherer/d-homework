<?php

namespace Src\Controllers;

class RegistrationController extends BaseController {

  public function getRegistration() {
    $template = $this->twig->load('registration.html');
    echo $template->render();
  }

  public function postRegistration() {
    $sql = "INSERT INTO user (email, name, password)
    VALUES (?, ?, ?)";

    $sth = $this->dbConnection->prepare($sql);
    $sth->execute(array($_POST['email'], $_POST['name'], $_POST['password']));

    mail("kidama62@gmail.com", "My subject", "pog");

    $this->getRegistration();
  }

}
