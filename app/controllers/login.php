<?php

require_once('../app/core/controller.php');

class Login extends Controller
{
    /**
     * index
     * load the User model and load the login view
     * @return view login
     */
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $user = $this->loadModel("User");
            $user->login();
        }

        $data['pageTitle'] = "Login";
        $this->view("login", $data);
    }
}
