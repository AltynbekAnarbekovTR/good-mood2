<?php

namespace App\Views;

use App\Config\Paths;
use Framework\TemplateEngine;

class AboutView extends View {
  public function renderAbout(array $data = [])
  {
    $this->templateEngine->render('about.php', $data);
  }
}