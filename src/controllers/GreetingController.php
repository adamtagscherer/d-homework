<?php

namespace Src\Controllers;

class GreetingController extends BaseController {

  public function getGreeting() {
    $user = $this->fetchUser();
    $this->render('greeting', [
      'name' => $user['name']
    ]);
  }

}
