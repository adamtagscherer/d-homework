<?php

namespace Src\Controllers;

class LogoutController extends BaseController {

  public function getLogout() {
    \session_unset();
    $this->redirect('login');
  }

}
