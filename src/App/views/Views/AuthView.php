<?php

namespace App\Views;

class AuthView extends View {
  public function renderRegisterUser(array $data = [])
  {
    $this->templateEngine->render('auth/register.php', $data);
  }

  public function renderEmailSent(array $data = [])
  {
    $this->templateEngine->render('auth/emailSent.php', $data);
  }

  public function renderLogin(array $data = [])
  {
    $this->templateEngine->render('auth/login.php', $data);
  }

  public function renderRestorePassword(array $data = [])
  {
    $this->templateEngine->render('auth/restorePassword.php', $data);
  }

  public function renderResetPassword(array $data = [])
  {
    $this->templateEngine->render('auth/resetPassword.php', $data);
  }
}