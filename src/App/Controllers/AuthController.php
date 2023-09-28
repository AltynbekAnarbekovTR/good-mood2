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
    $this->formValidatorService->addRulesToField('emailRules', ['required', 'email', 'lengthMax:100']);
    $this->formValidatorService->addRulesToField('passwordRules', ['required', 'password']);
    $this->formValidatorService->addRulesToField('confirmPasswordRules', ['required', 'match:password']);
    $this->formValidatorService->validateRegister($_POST);
    $email = $_POST['email'];
    $this->userModel->isEmailTaken($email);
    $this->userModel->create($_POST);
    $authenticationCode = md5((string)rand());
    $this->userModel->setAuthCode($authenticationCode, $email);
    $emailText = "<p>Welcome to Good Mood! Click the link below to verify your account</p><br/><a href='http://localhost/verifyAccount?code=$authenticationCode&email=$email'>Click to verify your email</a>";
    $this->emailService->sendEmail($emailText, $email);
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
      if ($codeInDb === $codeFromEmail) {
        $this->userModel->verifyAccount($email);
        redirectTo('/profile');
      }
    }
  }

  public function renderRestorePassword()
  {
    $this->view->render("auth/restorePassword.php");
  }

  public function restorePassword()
  {
    $email = $_POST['email'];
    $this->userModel->checkUserExist($email);
    $emailText = "<p>Follow the link below to set the new password:</p><br/><a href='http://localhost/reset-password?email=$email'>Click to reset password</a>";
    $this->emailService->sendEmail($emailText, $_POST['email']);
    $this->view->render("auth/emailSent.php");
  }

  public function renderResetPassword()
  {
    $this->view->render("auth/resetPassword.php");
  }

  public function resetPassword()
  {
    $this->formValidatorService->addRulesToField('passwordRules', ['required', 'password']);
    $this->formValidatorService->addRulesToField('confirmPasswordRules', ['required', 'match:password']);
    $this->formValidatorService->validateRegister($_POST);
    $email =  $_GET['email'] ?? '';
    if (!strlen($email)) {
      $user = $this->userModel->getUserById($_SESSION['user']['userId']);
      $email = $user['email'];
    }
    $this->userModel->changePassword($_POST['password'], $email);

    redirectTo('/profile');
  }
}
