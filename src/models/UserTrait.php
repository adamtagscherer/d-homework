<?php

namespace Src\Models;

trait UserTrait {

  /**
   * Insert a new user into the user table.
   *
   * @param array $params
   * @return void
   */
  protected function insertUser(array $params) {
    $sql =
    "INSERT INTO user (email, name, password)
    VALUES (?, ?, ?)";

    $sth = $this->dbConnection->prepare($sql);
    $sth->execute([$params['email'], $params['name'], \password_hash($params['password'], PASSWORD_DEFAULT)]);
  }

  /**
   * Selects a user from the user table by it's email address.
   *
   * @param string $email
   * @return void
   */
  protected function fetchUser($email) {
    $sql =
    "SELECT *
    FROM user
    WHERE email=?";

    $sth = $this->dbConnection->prepare($sql);
    isset($email) ? $sth->execute([$email]) : $sth->execute([$_SESSION['email']]);

    return $sth->fetch(\PDO::FETCH_ASSOC);
  }

  /**
   * Sets a user's active field to true.
   *
   * @param array $tokenEntity
   * @return void
   */
  protected function setUserActive(array $tokenEntity) {
    $sql =
    "UPDATE user
    SET active=1
    WHERE email=?";

    $sth = $this->dbConnection->prepare($sql);
    $sth->execute([$tokenEntity['email']]);
  }

}
