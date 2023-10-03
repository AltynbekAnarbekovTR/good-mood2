<?php

namespace App\Views;

class HomeView extends View {
  public function renderHome(array $data = [])
  {
    $this->templateEngine->render('index.php', $data);
  }
}