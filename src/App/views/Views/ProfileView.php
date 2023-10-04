<?php

namespace App\Views;

class ProfileView extends View {
  public function renderProfile(array $data = [])
  {
    $this->templateEngine->render('profile/profile.php', $data);
  }

  public function renderChangeUsername(array $data = [])
  {
    $this->templateEngine->render('profile/changeUsername.php', $data);
  }

  public function renderChangeEmail(array $data = [])
  {
    $this->templateEngine->render('profile/changeEmail.php', $data);
  }
}