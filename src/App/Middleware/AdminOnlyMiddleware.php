<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;

class AdminOnlyMiddleware implements MiddlewareInterface
{
  public function process(callable $next)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
      redirectTo('/login');
    }

    $next();
  }
}
