<?php

namespace Src\Controllers;

class GreetingController extends BaseController {

  public function getGreeting() {
    $user = $this->fetchUser(null);
    $template = $this->twig->load('greeting.html');
    echo $template->render([
      'name' => $user['name']
    ]);
  }

}
