<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\AuthCode;
use App\Views\{FormView, LayoutView, AuthView, LoginView};
use App\Services\{ErrorMessagesService, FormValidatorService, EmailService};
use App\Models\User;


class AuthController
{
  public function __construct(
          private AuthView $authView,
          private FormView $formView,
          private LoginView $loginView,
          private LayoutView $layoutView,
          private FormValidatorService $formValidatorService,
          private User $userModel,
          private EmailService $emailService,
          private AuthCode $authCodeModel,
          private ErrorMessagesService $errorMessagesService
  ) {
  }

  public function renderRegisterUser()
  {
    $registerForm = $this->formView->createFormTemplate(
            [
                    ['label' => 'Username', 'name' => 'username', 'placeholder' => 'John'],
                    ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'placeholder' => 'john@example.com'],
                    ['label' => 'Password', 'name' => 'password', 'type' => 'password'],
                    ['label' => 'Confirm Password', 'name' => 'confirmPassword', 'type' => 'password'],
            ]
    );
    $this->layoutView->renderPage($registerForm);
  }

  public function registerUser()
  {
    $this->formValidatorService->addRulesToField('username', ['required', 'lengthMax:50']);
    $this->formValidatorService->addRulesToField('email', ['required', 'email', 'lengthMax:100']);
    $this->formValidatorService->addRulesToField('password', ['required', 'password']);
    $this->formValidatorService->addRulesToField('confirmPassword', ['required', 'match:password']);
    $errors = [];
    $errors += $this->formValidatorService->validate($_POST);
    $email = $_POST['email'];
    if ($this->userModel->isEmailTaken($email)) {
      $errors += ['email' => ['Email taken']];
    }
    if (count($errors)) {
      $this->errorMessagesService->setErrorMessage($errors);
    }
    $this->userModel->create($_POST);
    session_regenerate_id();
    $_SESSION['user'] = ['userId' => $this->userModel->lastInsertId()];
    $authenticationCode = md5((string)rand());
    $this->authCodeModel->setAuthCode($authenticationCode, $email);
    $emailText = "<p>Welcome to Good Mood! Click the link below to verify your account</p><br/><a href='http://localhost/verify-account?code=$authenticationCode&email=$email'>Click to verify your email</a>";
    $this->emailService->sendEmail($emailText, $email);
    $emailSentTemplate = $this->authView->getEmailSentTemplate();
    $this->layoutView->renderPage($emailSentTemplate);
  }

  public function renderLoginUser()
  {
    $loginForm = $this->loginView->createLoginTemplate();
    $this->layoutView->renderPage($loginForm);
  }

  public function login()
  {
    $this->formValidatorService->addRulesToField('email', ['required']);
    $this->formValidatorService->addRulesToField('password', ['required']);
    $errors = [];
    $errors += $this->formValidatorService->validate($_POST);
    $user = $this->userModel->login($_POST);
    if ($user) {
      $passwordsMatch = password_verify(
              $_POST['password'],
              $user->getPassword() ?? ''
      );
    }
    if (!$user || !$passwordsMatch) {
      $errors += ['password' => ['Invalid credentials']];
    }
    if (count($errors)) {
      $this->errorMessagesService->setErrorMessage($errors);
    }
    if (!$user->getEmailVerified()) {
      $this->errorMessagesService->setErrorMessage(['otherLoginErrors' => ['You need to verify your email']]);
    }
    $this->setUserToSession($user);
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
        $user = $this->userModel->verifyAccount($email);
        $this->setUserToSession($user);
        redirectTo('/profile');
      }
    }
  }

  public function renderRestorePassword()
  {
    $restorePasswordTemplate = $this->authView->getRestorePasswordTemplate();
    $this->layoutView->renderPage($restorePasswordTemplate);
  }

  public function restorePassword()
  {
    $email = $_POST['email'];
    $userExist = $this->userModel->checkUserExist($email);
    if (!$userExist) {
      $this->errorMessagesService->setErrorMessage(['email' => ['Such user doesn\'t exist']]);
    }
    $emailText = "<p>Follow the link below to set the new password:</p><br/><a href='http://localhost/reset-password?email=$email'>Click to reset password</a>";
    $this->emailService->sendEmail($emailText, $_POST['email']);
    $emailSentTemplate = $this->authView->getEmailSentTemplate();
    $this->layoutView->renderPage($emailSentTemplate);
  }

  public function renderResetPassword()
  {
    $changePasswordForm = $this->formView->createFormTemplate(
            [
                    ['label' => 'Password', 'name' => 'password', 'type' => 'password'],
                    ['label' => 'Confirm Password', 'name' => 'confirmPassword', 'type' => 'password'],
            ]
    );
    $this->layoutView->renderPage($changePasswordForm);
  }

  public function resetPassword()
  {
    $this->formValidatorService->addRulesToField('password', ['required', 'password']);
    $this->formValidatorService->addRulesToField('confirmPassword', ['required', 'match:password']);
    $errors = $this->formValidatorService->validate($_POST);
    if(count($errors)) {
      $this->errorMessagesService->setErrorMessage($errors);
    }
    $email = $_GET['email'] ?? '';
    if (!strlen($email)) {
      $user = $this->userModel->getUserById($_SESSION['user']['userId']);
      $email = $user->getEmail();
    }
    $this->userModel->changePassword($_POST['password'], $email);
    redirectTo('/profile');
  }

  public function setUserToSession(User $user)
  {
    session_regenerate_id();
    $_SESSION['user'] = ['userId' => $user->getId()];
    $_SESSION['user']['loggedIn'] = true;
    $_SESSION['user']['username'] = $user->getUsername();
    $_SESSION['user']['role'] = $user->getRole();
  }
}
