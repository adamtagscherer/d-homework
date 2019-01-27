<?php

namespace Src\Models;

trait LoginAttemptTrait {

  protected function invalidLoginAttempt($user, $password) {
    $sql =
    "INSERT INTO login_attempt (email, password)
    VALUES (?, ?)";

    $sth = $this->dbConnection->prepare($sql);
    $sth->execute([$user['email'] ?? $user, $password]);
  }

}
