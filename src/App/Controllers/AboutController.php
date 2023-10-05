<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Views\{LayoutView, AboutView};

class AboutController
{
  public function __construct( private AboutView $aboutView, private LayoutView $layoutView)
  {
  }

  public function renderAbout()
  {
    $aboutPageTemplate = $this->aboutView->getAboutTemplate();
    $this->layoutView->renderPage($aboutPageTemplate);
  }
}
