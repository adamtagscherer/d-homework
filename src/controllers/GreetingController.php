<?php

namespace Src\Controllers;

class GreetingController extends BaseController {

  public function getGreeting() {
    $template = $this->twig->load('greeting.html');
    echo $template->render();
  }

}
