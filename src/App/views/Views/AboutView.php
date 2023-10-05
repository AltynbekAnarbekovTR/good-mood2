<?php

namespace App\Views;

use App\Config\Paths;
use Framework\TemplateEngine;

class AboutView extends View {
  public function getAboutTemplate(array $data = [])
  {
    return $this->templateEngine->render('about.php', $data);
  }
}