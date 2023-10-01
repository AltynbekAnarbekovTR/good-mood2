<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\AuthCode;
use Framework\TemplateEngine;
use App\Services\{FormValidatorService, EmailService};
use App\Models\User;


class AuthController
{
  public function __construct(
          private TemplateEngine $view,
          private FormValidatorService $formValidatorService,
          private User $userModel,
          private EmailService $emailService,
          private AuthCode $authCodeModel
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
    $this->authCodeModel->setAuthCode($authenticationCode, $email);
    $emailText = "<p>Welcome to Good Mood! Click the link below to verify your account</p><br/><a href='http://localhost/verify-account?code=$authenticationCode&email=$email'>Click to verify your email</a>";
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

    if ($user->getRole() === 'admin') {
      redirectTo('/manage-articles');
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
      $authCode = $this->authCodeModel->getAuthCode($email);
      if ($authCode->getCode() === $codeFromEmail) {
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
      $email = $user->getEmail();
    }
    $this->userModel->changePassword($_POST['password'], $email);

    redirectTo('/profile');
  }
}
