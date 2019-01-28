<?php

namespace Src\Models;

trait RegistrationTokenTrait
{

    /**
     * Insert new registration token assigned to email.
     *
     * @param string $email
     * @param string $token
     * @return void
     */
    protected function insertRegistrationToken(string $email, string $token)
    {
        $sql = "INSERT INTO registration_token (email, token) VALUES (?, ?)";

        $sth = $this->dbConnection->prepare($sql);
        $sth->execute([$email, $token]);
    }

    /**
     * Selects a token and sets it's deleted_at attribute if it's older than one day.
     * If there's a valid token then returns it.
     *
     * @param string $token
     * @return void
     */
    protected function getTokenEntity(string $token)
    {
        $sql = "SELECT * FROM registration_token WHERE token=? AND deleted_at IS NULL";

        $sth = $this->dbConnection->prepare($sql);
        $sth->execute([$token]);

        $tokenEntity = $sth->fetch(\PDO::FETCH_ASSOC);

        if ($this->isTokenExpired($tokenEntity)) {
            $this->deleteToken($tokenEntity);
            return false;
        }

        return $tokenEntity;
    }

    /**
     * Checks if the given token is older than one day.
     *
     * @param array $tokenEntity
     * @return bool
     */
    protected function isTokenExpired(array $tokenEntity): bool
    {
        return strtotime($tokenEntity['created_at']) < (time() - (60 * 60 * 24));
    }

    /**
     * Updates a token's deleted_at field to the current datetime.
     *
     * @param array $tokenEntity
     * @return void
     */
    protected function deleteToken(array $tokenEntity)
    {
        $sql = "UPDATE registration_token SET deleted_at=now() WHERE email=?";

        $sth = $this->dbConnection->prepare($sql);
        $sth->execute([$tokenEntity['email']]);
    }

    /**
     * Deletes the tokens assigned to the given email then generates a new token inserts it to the database
     * and sends an email with it's URL.
     *
     * @param string $email
     * @return void
     */
    protected function sendNewToken(string $email)
    {
        $this->deleteToken(['email' => $email]);
        $token = $this->generateRegistrationToken(32);
        $this->insertRegistrationToken($email, $token);
        $this->sendVerificationEmail($email, $token);
    }

}
