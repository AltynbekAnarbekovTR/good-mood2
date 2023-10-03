<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Views\AboutView;

class AboutController
{
  public function __construct( private AboutView $aboutView,)
  {
  }

  public function renderAbout()
  {
    $this->aboutView->renderAbout([
    ]);
  }
}
