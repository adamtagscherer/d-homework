<?php

namespace Src\Controllers;

class LoginController extends BaseController
{

    /**
     * Renders login page.
     *
     * @return void
     */
    public function getLogin()
    {
        if (isset($_GET['action']) && $_GET['action'] === 'successful-registration') {
            $params['success'] = 'Successful account activation.';
        }

        if (isset($_GET['action']) && $_GET['action'] === 'unsuccessful-registration') {
            $params['error'] = 'Token expired.';
        }

        $this->render('login', $params ?? []);
    }

    /**
     * Handles the login page HTTP POST action.
     *
     * @return void
     */
    public function postLogin()
    {

        $user = $this->preValidateLogin($_POST);

        if ($user) {
            $loginAttempt = $this->authenticate($_POST['password'], $user['password']);

            if ($loginAttempt) {
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $user['name'];
                $this->redirect('admin/greeting');
            }

            $this->invalidLoginAttempt($user['email'], $_POST['password']);
            $this->render('login', [
                'error' => 'Wrong credentials.',
            ]);
        }

        $this->invalidLoginAttempt($_POST['email'], $_POST['password']);
        $this->render('login', [
            'error' => 'No user found.',
        ]);

    }

    /**
     * Login page HTTP POST action validation logic.
     *
     * @param array $params
     * @return void
     */
    private function preValidateLogin(array $params)
    {
        if (empty($params['email'])) {
            $this->render('login', [
                'error' => 'Please provide email.',
            ]);
        }

        if (empty($params['password'])) {
            $this->render('login', [
                'error' => 'Please provide password.',
            ]);
        }

        $user = $this->fetchUser($params['email']);

        if ($user && !$user['active']) {
            $this->render('login', [
                'error' => 'The account is not activated yet.',
            ]);
        }

        if ($user) {
            return $user;
        }

    }

}
