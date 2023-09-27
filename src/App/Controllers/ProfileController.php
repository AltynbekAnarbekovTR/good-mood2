<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Articles\ArticleModel;
use App\Models\Users\UserModel;
use App\Services\EmailService;
use App\Services\FormValidatorService;
use App\Services\UploadFileService;
use Framework\TemplateEngine;

class ProfileController
{


  public function __construct(
          private TemplateEngine $view,
          private UploadFileService $uploadFileService,
          private UserModel $userModel,
          private ArticleModel $articleModel,
          private FormValidatorService $formValidatorService,
          private EmailService $emailService
  ) {
  }

  public function renderProfile()
  {
    $user = $this->userModel->getUserById($_SESSION['user']['id']);
    if ($user['storage_filename']) {
      $user = $this->articleModel->attachImageToArticle($user);
    }
    $this->view->render(
            'profile/profile.php',
            [
                    'user' => $user,
            ]
    );
  }

  public function changeAvatar()
  {
    $this->uploadFileService->checkUploadIsImage($_FILES['avatar']);
    $newFilename = $this->uploadFileService->uploadImageToStorage($_FILES['avatar']);
    $this->userModel->changeAvatar($_FILES['avatar'], $newFilename, $_SESSION['user']['id']);
    redirectTo($_SERVER['HTTP_REFERER']);
  }

  public function renderChangeUsername()
  {
    $this->view->render(
            'profile/changeUsername.php',
    );
  }

  public function changeUsername()
  {
    $this->formValidatorService->addRulesToField('usernameRules', ['required', 'lengthMax:50']);
    $this->formValidatorService->validateRegister($_POST);
    $this->userModel->changeUsername($_POST['username'], $_SESSION['user']['id']);
    redirectTo('/profile');
  }

  public function renderChangeEmail()
  {
    $this->view->render(
            'profile/changeEmail.php',
    );
  }

  public function changeEmail()
  {
    $this->formValidatorService->addRulesToField('emailRules', ['required', 'email']);
    $this->formValidatorService->validateRegister($_POST);
    $email = $_POST['email'];
    $this->userModel->isEmailTaken($email);
    $authenticationCode = md5((string)rand());
    $this->userModel->setAuthCode($authenticationCode, $email);
    $emailText = "<p>You sent a request to change your email in Good Mood. Click the link below to verify your new email.</p><br/><a href='http://localhost/verifyEmailChange?code=$authenticationCode&email=$email'>Click to verify your email</a>";
    $this->emailService->sendEmail($emailText, $email);
    $this->view->render("auth/emailSent.php");
  }

  public function verifyEmailChange()
  {
    $codeFromEmail = $_GET['code'] ?? null;
    $email = $_GET['email'] ?? null;
    if ($codeFromEmail && $email) {
      $codeInDb = $this->userModel->getAuthCode($email);
      if ($codeInDb === $codeFromEmail) {
        $this->userModel->changeEmail($email, $_SESSION['user']['id']);
        $_SESSION['user']['email'] = $email;
        redirectTo('/');
      }
    }
  }
}
