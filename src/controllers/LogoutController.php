<?php

namespace Src\Controllers;

class LogoutController extends BaseController {

  /**
   * Logs out the user.
   *
   * @return void
   */
  public function getLogout() {
    \session_unset();
    $this->redirect('login');
  }

}
