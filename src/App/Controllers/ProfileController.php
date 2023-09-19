<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;

class ProfileController
{

  public function __construct(
          private TemplateEngine $view,
  ) {
  }

  public function renderProfile()
  {
    $this->view->render(
            'profile.php',
            [
                    'user' => $_SESSION['user'],
            ]
    );
  }
}
