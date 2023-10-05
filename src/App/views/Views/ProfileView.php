<?php

namespace App\Views;

class ProfileView extends View {
  public function getProfileTemplate(array $data = []): string
  {
    return $this->templateEngine->render('profile/profile.php', $data);
  }

  public function getChangeUsernameTemplate(array $data = []): string
  {
    return $this->templateEngine->render('profile/changeUsername.php', $data);
  }

  public function getChangeEmailTemplate(array $data = []): string
  {
    return $this->templateEngine->render('profile/changeEmail.php', $data);
  }
}