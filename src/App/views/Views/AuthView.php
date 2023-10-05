<?php

namespace App\Views;

class AuthView extends View {
  public function getRegisterUserTemplate(array $data = [])
  {
    return $this->templateEngine->render('auth/register.php', $data);
  }

  public function getEmailSentTemplate(array $data = [])
  {
    return $this->templateEngine->render('auth/emailSent.php', $data);
  }

  public function getLoginTemplate()
  {
    return $this->templateEngine->render('auth/login.php');
  }

  public function getRestorePasswordTemplate(array $data = [])
  {
    return $this->templateEngine->render('auth/restorePassword.php', $data);
  }

  public function getResetPasswordTemplate(array $data = [])
  {
    return $this->templateEngine->render('auth/resetPassword.php', $data);
  }
}