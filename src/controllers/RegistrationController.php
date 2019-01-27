<?php

namespace Src\Controllers;

class RegistrationController extends BaseController {

  public function getRegistration() {
    $this->render('registration', []);
  }

  public function postRegistration() {
    $user = $this->fetchUser($_POST['email']);

    if($user && $user['active']) {
      $this->render('registration', [
        'error' => 'There is an active account registered with this email.'
      ]);
    }

    if($user && !$user['active']) {
      $this->generateNewToken($_POST['email']);
      $this->render('registration', [
        'error' => 'There is an account registered with this email but it\'s not active. New token sent to this e-mail address.'
      ]);
    }

    if(!$this->validString($_POST['password'])) {
      $this->render('registration', [
        'error' => 'The password must be at least 6 characters long and contain small letter, capital letter, digit and special character.'
      ]);
    }

    $token = $this->generateRegistrationToken(32);
    $this->insertUser($_POST);
    $this->sendVerificationEmail($_POST['email'], $token);
    $this->insertRegistrationToken($_POST['email'], $token);

    $this->getRegistration();
  }

  private function validString($string) {
    $containsSmallLetter = preg_match('/[a-z]/', $string);
    $containsCapsLetter = preg_match('/[A-Z]/', $string);
    $containsDigit = preg_match('/\d/', $string);
    $containsSpecial = preg_match('/[^a-zA-Z\d]/', $string);
    $length = strlen($string) >= 6;
    return ($containsSmallLetter && $containsCapsLetter && $containsDigit && $containsSpecial && $length);
  }

  protected function sendVerificationEmail($email, $token) {

    $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
    ->setUsername(EMAIL_USER)
    ->setPassword(EMAIL_PASSWORD);

    $mailer = new \Swift_Mailer($transport);

    $registrationLink = SERVER_NAME . APP_PATH . '/registration-token' . '/' . $token;

    $messageBody = 'To complete the registration process, please click on the link below. The token is active for 24h only.<br/><br/>' . '<a href="' . $registrationLink . '" >Activation Link</a>';

    $message = (new \Swift_Message('Registration verification'))
    ->setFrom(['noreply@docler.com' => 'Docler Holding'])
    ->setTo([$email => 'Dear User'])
    ->setBody($messageBody, 'text/html');

    $result = $mailer->send($message);
  }

  function generateRegistrationToken($length) {
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
