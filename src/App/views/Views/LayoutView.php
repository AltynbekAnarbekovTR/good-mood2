<?php

namespace App\Views;

class LayoutView extends View {
  public function renderPage($content, array $data = [] )
  {
    $data += ['content' => $content];
    echo $this->templateEngine->render('layout/layout.php', $data);
  }
}