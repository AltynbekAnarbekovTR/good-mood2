<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\FormValidatorService;
use App\Models\Users\UserModel;


class AuthController
{
  public function __construct(
          private TemplateEngine $view,
          private FormValidatorService $formValidatorService,
          private UserModel $userModel
  ) {
  }

  public function renderRegisterUser()
  {
    $this->view->render("register.php");
  }

  public function registerUser()
  {
    $this->formValidatorService->validateRegister($_POST);
    $this->userModel->isEmailTaken($_POST['email']);
    $this->userModel->create($_POST);
    $authenticationCode = md5((string)rand());
    $this->userModel->setAuthCode($authenticationCode, $_POST['email']);
    $this->userModel->sendVerificationEmail($authenticationCode, $_POST['email']);
    redirectTo('/');
  }

  public function renderLoginUser()
  {
    $this->view->render("login.php");
  }

  public function login()
  {
    $this->formValidatorService->validateLogin($_POST);

    $user = $this->userModel->login($_POST);

    if ($user->role === 'admin') {
      redirectTo('/articles');
    } else {
      redirectTo('/');
    }
  }

  public function logout()
  {
    $this->userModel->logout();
    redirectTo('/login');
  }

  public function checkAccountVerification()
  {
    $codeFromEmail = $_GET['code'] ?? null;
    $email = $_GET['email'] ?? null;
    if ($codeFromEmail && $email) {
      $codeInDb = $this->userModel->getAuthCode($email);
      if($codeInDb === $codeFromEmail) {
        $this->userModel->verifyAccount($email);
        redirectTo('/');
      }
    }
  }
}
