<?php

namespace Src\Models;

trait RegistrationTokenTrait {

  protected function insertRegistrationToken($email, $token) {
    $sql =
    "INSERT INTO registration_token (email, token)
    VALUES (?, ?)";

    $sth = $this->dbConnection->prepare($sql);
    $sth->execute([$email, $token]);
  }

  protected function getTokenEntity($token) {
    $sql =
    "SELECT *
    FROM registration_token
    WHERE token=? AND deleted_at IS NULL";

    $sth = $this->dbConnection->prepare($sql);
    $sth->execute([$token]);

    $tokenEntity = $sth->fetch(\PDO::FETCH_ASSOC);

    if($this->isTokenExpired($tokenEntity)) {
      $this->deleteToken($tokenEntity);
      return false;
    }

    return $tokenEntity;
  }

  protected function isTokenExpired($tokenEntity) {
    return strtotime($tokenEntity['created_at']) < (time() - (60 * 60 * 24));
  }

  protected function deleteToken($tokenEntity) {
    $sql =
    "UPDATE registration_token
    SET deleted_at=now()
    WHERE email=?";

    $sth = $this->dbConnection->prepare($sql);
    $sth->execute([$tokenEntity['email']]);
  }

  protected function generateNewToken($email) {
    $this->deleteToken(['email' => $email]);
    $token = $this->generateRegistrationToken(32);
    $this->insertRegistrationToken($email, $token);
    $this->sendVerificationEmail($email, $token);
  }

}
