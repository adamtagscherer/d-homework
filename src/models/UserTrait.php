<?php

namespace Src\Models;

trait UserTrait {

  protected function insertUser($params) {
    $sql =
    "INSERT INTO user (email, name, password)
    VALUES (?, ?, ?)";

    $sth = $this->dbConnection->prepare($sql);
    $sth->execute([$params['email'], $params['name'], \password_hash($params['password'], PASSWORD_DEFAULT)]);
  }

  protected function fetchUser($email) {
    $sql =
    "SELECT *
    FROM user
    WHERE email=?";

    $sth = $this->dbConnection->prepare($sql);
    $sth->execute([$email ?? $_SESSION['email']]);

    return $sth->fetch(\PDO::FETCH_ASSOC);
  }

  protected function setUserActive($tokenEntity) {
    $sql =
    "UPDATE user
    SET active=1
    WHERE email=?";

    $sth = $this->dbConnection->prepare($sql);
    $sth->execute([$tokenEntity['email']]);
  }

}
