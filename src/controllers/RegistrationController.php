<?php

namespace Src\Controllers;

class RegistrationController extends BaseController {

  /**
   * Renders the registration page.
   *
   * @return void
   */
  public function getRegistration() {
    $this->render('registration', []);
  }

  /**
   * Main registration HTTP POST logic.
   *
   * @return void
   */
  public function postRegistration() {
    $user = $this->fetchUser($_POST['email']);

    if($user && $user['active']) {
      $this->render('registration', [
        'error' => 'There is an active account registered with this email.'
      ]);
    }

    if($user && !$user['active']) {
      $this->sendNewToken($_POST['email']);
      $this->render('registration', [
        'error' => 'There is an account registered with this email but it\'s not active. New token sent to this e-mail address.'
      ]);
    }

    if(!$this->isPasswordStrong($_POST['password'])) {
      $this->render('registration', [
        'error' => 'The password must be at least 6 characters long and contain small letter, capital letter, digit and special character.'
      ]);
    }

    $this->insertUser($_POST);
    $this->sendNewToken($_POST['email']);

    $this->getRegistration();
  }

  /**
   * Checks whether the given password is stron enough. The password must contain small and capital letters,
   * digit, special character and must be at least 6 character long.
   *
   * @param string $string
   * @return boolean
   */
  private function isPasswordStrong(string $string) : boolean {
    $containsSmallLetter = preg_match('/[a-z]/', $string);
    $containsCapsLetter = preg_match('/[A-Z]/', $string);
    $containsDigit = preg_match('/\d/', $string);
    $containsSpecial = preg_match('/[^a-zA-Z\d]/', $string);
    $length = strlen($string) >= 6;
    return ($containsSmallLetter && $containsCapsLetter && $containsDigit && $containsSpecial && $length);
  }

  /**
   * Sends verification email through gmail. It uses the predefined gmail credentials in config.php.
   *
   * @param string $email
   * @param string $token
   * @return void
   */
  protected function sendVerificationEmail(string $email, string $token) {

    $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
    ->setUsername(EMAIL_USER)
    ->setPassword(EMAIL_PASSWORD);

    $mailer = new \Swift_Mailer($transport);

    $registrationLink = SERVER_NAME . APP_PATH . '/registration-token' . '/' . $token;

    $messageBody = 'To complete the registration process, please click on the link below. The token is active for 24h only.<br/><br/>' .
    '<a href="' . $registrationLink . '" >Activation Link</a>';

    $message = (new \Swift_Message('Registration verification'))
    ->setFrom(['noreply@docler.com' => 'Docler Holding'])
    ->setTo([$email => 'Dear User'])
    ->setBody($messageBody, 'text/html');

    $mailer->send($message);
  }

  /**
   * Generates a unique token for registration verification.
   *
   * @param integer $length
   * @return string
   */
  function generateRegistrationToken(int $length) : string {
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet);

    for ($i=0; $i < $length; $i++) {
      $token .= $codeAlphabet[random_int(0, $max-1)];
    }

    return $token;
  }

  /**
   * Sets a user's active flag to true.
   *
   * @param [type] $request
   * @return void
   */
  public function activateUser($request) {
    $tokenEntity = $this->getTokenEntity($request->token);
    if($tokenEntity) {
      $this->setUserActive($tokenEntity);
      $this->deleteToken($tokenEntity);
      $this->redirect('login?action=successful-registration');
    }
    $this->redirect('login?action=unsuccessful-registration');
  }

}
