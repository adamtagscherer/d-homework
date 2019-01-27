<?php

namespace Src\Controllers;

class GreetingController extends BaseController
{

    /**
     * Renders the greeting page.
     *
     * @return void
     */
    public function getGreeting()
    {
        $user = $this->fetchUser(null);
        $this->render('greeting', [
            'name' => $user['name'],
        ]);
    }

}
