<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\AuthCode;
use App\Models\User;
use App\Services\EmailService;
use App\Services\ErrorMessagesService;
use App\Services\FormValidatorService;
use App\Services\ImageService;
use App\Services\UploadFileService;
use App\Views\AuthView;
use App\Views\ProfileView;
use Framework\TemplateEngine;

class ProfileController
{


  public function __construct(
          private ProfileView $profileView,
          private AuthView $authView,
          private UploadFileService $uploadFileService,
          private User $userModel,
          private FormValidatorService $formValidatorService,
          private EmailService $emailService,
          private ImageService $imageService,
          private AuthCode $authCodeModel,
          private ErrorMessagesService $errorMessagesService
  ) {
  }

  public function renderProfile()
  {
    $user = $this->userModel->getUserById($_SESSION['user']['userId']);
    $userAvatar = null;
    if ($user->getStorageFilename()) {
      $userAvatar = $this->imageService->createB64Image($user);
    }
    $this->profileView->renderProfile(
            [
                    'user' => $user,
              'userAvatar' => $userAvatar
            ]
    );
  }

  public function changeAvatar()
  {
    $errorMessage = $this->uploadFileService->checkUploadedImage($_FILES['avatar']);
    if ($errorMessage) {
      $this->errorMessagesService->setErrorMessage($errorMessage);
    }
    [$newFilename, $fileIsUploaded] = $this->uploadFileService->uploadImageToStorage($_FILES['avatar']);
    if (!$fileIsUploaded) {
      $this->errorMessagesService->setErrorMessage(['cover' => ['Failed to upload file']]);
    }
    $this->userModel->changeAvatar($_FILES['avatar'], $newFilename, $_SESSION['user']['userId']);
    redirectTo($_SERVER['HTTP_REFERER']);
  }

  public function renderChangeUsername()
  {
    $this->profileView->renderChangeUsername();
  }

  public function changeUsername()
  {
    $this->formValidatorService->addRulesToField('username', ['required', 'lengthMax:50']);
    $this->formValidatorService->validate($_POST);
    $this->userModel->changeUsername($_POST['username'], $_SESSION['user']['userId']);
    redirectTo('/profile');
  }

  public function renderChangeEmail()
  {
    $this->profileView->renderChangeEmail();
  }

  public function changeEmail()
  {
    $this->formValidatorService->addRulesToField('email', ['required', 'email']);
    $this->formValidatorService->validate($_POST);
    $email = $_POST['email'];
    if($this->userModel->isEmailTaken($email)){
      $this->errorMessagesService->setErrorMessage(['email' => ['Email taken']]);
    }
    $authenticationCode = md5((string)rand());
    $this->authCodeModel->setAuthCode($authenticationCode, $email);
    $emailText = "<p>You sent a request to change your email in Good Mood. Click the link below to verify your new email.</p><br/><a href='http://localhost/verify-email-change?code=$authenticationCode&email=$email'>Click to verify your email</a>";
    $this->emailService->sendEmail($emailText, $email);
    $this->authView->renderEmailSent();
  }

  public function verifyEmailChange()
  {
    $codeFromEmail = $_GET['code'] ?? null;
    $email = $_GET['email'] ?? null;
    if ($codeFromEmail && $email) {
      $authCode = $this->authCodeModel->getAuthCode($email);
      if ($authCode->getCode() === $codeFromEmail) {
        $this->userModel->changeEmail($email, $_SESSION['user']['userId']);
        redirectTo('/profile');
      }
    }
  }
}
