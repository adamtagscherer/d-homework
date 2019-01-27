<?php

namespace Src\Models;

trait LoginAttemptTrait {

  /**
   * Insert invalid login attempt.
   *
   * @param string $email
   * @param string $password
   * @return void
   */
  protected function invalidLoginAttempt(string $email, string $password) {
    $sql =
    "INSERT INTO login_attempt (email, password)
    VALUES (?, ?)";

    $sth = $this->dbConnection->prepare($sql);
    $sth->execute([$email, $password]);
  }

}
