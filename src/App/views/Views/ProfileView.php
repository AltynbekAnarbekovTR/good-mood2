<?php

namespace App\Views;

class ProfileView extends View {
  public function getProfileTemplate(array $data = []): string
  {
    return $this->templateEngine->render('user/profile.php', $data);
  }

  public function getChangeUsernameTemplate(array $data = []): string
  {
    return $this->templateEngine->render('user/changeUsername.php', $data);
  }

  public function getChangeEmailTemplate(array $data = []): string
  {
    return $this->templateEngine->render('user/changeEmail.php', $data);
  }
}