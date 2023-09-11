<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\ValidatorService;
use App\Models\Users\UserModel;


class AuthController
{
    public function __construct(
        private TemplateEngine $view,
        private ValidatorService $validatorService,
        private UserModel $userModel
    ) {
    }

    public function renderRegisterUser()
    {
        $this->view->render("register.php");
    }

    public function registerUser()
    {
        $this->validatorService->validateRegister($_POST);

        $this->userModel->isEmailTaken($_POST['email']);

        $this->userModel->create($_POST);
        redirectTo('/');
    }

    public function renderLoginUser()
    {
        $this->view->render("login.php");
    }

    public function login()
    {
        $this->validatorService->validateLogin($_POST);

        $this->userModel->login($_POST);

        redirectTo('/');
    }

    public function logout()
    {
        $this->userModel->logout();

        redirectTo('/login');
    }
}
