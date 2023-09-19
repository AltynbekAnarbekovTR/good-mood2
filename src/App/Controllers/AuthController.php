<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{FormValidatorService, EmailService};
use App\Models\Users\UserModel;


class AuthController
{
  public function __construct(
          private TemplateEngine $view,
          private FormValidatorService $formValidatorService,
          private UserModel $userModel,
          private EmailService $emailService
  ) {
  }

  public function renderRegisterUser()
  {
    $this->view->render("auth/register.php");
  }

  public function registerUser()
  {
    $this->formValidatorService->addRulesToField('usernameRules', ['required', 'lengthMax:50']);
    $this->formValidatorService->addRulesToField('emailRules', ['required', 'email']);
    $this->formValidatorService->addRulesToField('passwordRules', ['required', 'password']);
    $this->formValidatorService->addRulesToField('confirmPasswordRules', ['required', 'match:password']);
    $this->formValidatorService->validateRegister($_POST);
    $this->userModel->isEmailTaken($_POST['email']);
    $this->userModel->create($_POST);
    $authenticationCode = md5((string)rand());
    $this->userModel->setAuthCode($authenticationCode, $_POST['email']);
    $this->emailService->sendVerificationEmail($authenticationCode, $_POST['email']);
    $this->view->render("auth/emailSent.php");
  }

  public function renderLoginUser()
  {
    $this->view->render("auth/login.php");
  }

  public function login()
  {
    $this->formValidatorService->addRulesToField('emailRules', ['required']);
    $this->formValidatorService->addRulesToField('passwordRules', ['required']);
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
