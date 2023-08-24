<?php

declare(strict_types=1);

if (!defined('AUTOLOADER_INITIALIZED')) {
    require_once dirname(__DIR__) . '../../autoload.php';
}

use Framework\App;
use App\Controllers\{
  HomeController,
  AboutController,
  AuthController
};
use App\Middleware\{
  TemplateDataMiddleware,
  ValidationExceptionMiddleware,
  SessionMiddleware,
  FlashMiddleware
};

$app = new App(Paths::SOURCE . "App/container-definitions.php");

$app->get('/', [HomeController::class, 'home']);
$app->get('/about', [AboutController::class, 'about']);
$app->get('/register', [AuthController::class, 'registerView']);
$app->post('/register', [AuthController::class, 'register']);

$app->addMiddleware(TemplateDataMiddleware::class);
$app->addMiddleware(ValidationExceptionMiddleware::class);
$app->addMiddleware(FlashMiddleware::class);
$app->addMiddleware(SessionMiddleware::class);

return $app;
