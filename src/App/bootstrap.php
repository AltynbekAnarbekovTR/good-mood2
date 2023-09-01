<?php

declare(strict_types=1);

 if (!defined('AUTOLOADER_INITIALIZED')) {
    require __DIR__ . '/../../autoload.php';
 }



use Framework\App;
use App\Config\Paths;
use App\Middleware\{TemplateDataMiddleware,
    ValidationExceptionMiddleware,
    SessionMiddleware,
    FlashMiddleware,
    AuthRequiredMiddleware,
    GuestOnlyMiddleware,
    CsrfTokenMiddleware,
    CsrfGuardMiddleware,
};
use App\Controllers\{
    HomeController,
    AboutController,
    AuthController,
    ArticleController,
    ReceiptController
  };

$app = new App(Paths::SOURCE . "App/container-definitions.php");

$app->get('/', [HomeController::class, 'home'])->add(AuthRequiredMiddleware::class);
  $app->get('/about', [AboutController::class, 'about']);
  $app->get('/register', [AuthController::class, 'registerView'])->add(GuestOnlyMiddleware::class);
  $app->post('/register', [AuthController::class, 'register'])->add(GuestOnlyMiddleware::class);
  $app->get('/login', [AuthController::class, 'loginView'])->add(GuestOnlyMiddleware::class);
  $app->post('/login', [AuthController::class, 'login'])->add(GuestOnlyMiddleware::class);
  $app->get('/logout', [AuthController::class, 'logout'])->add(AuthRequiredMiddleware::class);
  $app->get('/article', [ArticleController::class, 'createView'])->add(AuthRequiredMiddleware::class);
  $app->post('/article', [ArticleController::class, 'create'])->add(AuthRequiredMiddleware::class);
  $app->get('/article/{article}', [ArticleController::class, 'editView'])->add(AuthRequiredMiddleware::class);
  $app->post('/article/{article}', [ArticleController::class, 'edit'])->add(AuthRequiredMiddleware::class);
  $app->delete('/article/{article}', [ArticleController::class, 'delete'])->add(AuthRequiredMiddleware::class);
  $app->get('/article/{article}/receipt', [ReceiptController::class, 'uploadView'])->add(AuthRequiredMiddleware::class);
  $app->post('/article/{article}/receipt', [ReceiptController::class, 'upload'])->add(AuthRequiredMiddleware::class);
  $app->get('/article/{article}/receipt/{receipt}', [ReceiptController::class, 'download'])->add(AuthRequiredMiddleware::class);
  $app->delete('/article/{article}/receipt/{receipt}', [ReceiptController::class, 'delete'])->add(AuthRequiredMiddleware::class);

$app->addMiddleware(CsrfGuardMiddleware::class);
$app->addMiddleware(CsrfTokenMiddleware::class);
$app->addMiddleware(TemplateDataMiddleware::class);
$app->addMiddleware(ValidationExceptionMiddleware::class);
$app->addMiddleware(FlashMiddleware::class);
$app->addMiddleware(SessionMiddleware::class);

return $app;
