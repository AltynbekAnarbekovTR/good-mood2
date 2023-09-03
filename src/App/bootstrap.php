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
    ArticleController
};

$app = new App(Paths::SOURCE . "App/container-definitions.php");

$app->get('/', [HomeController::class, 'renderHome'])->add(AuthRequiredMiddleware::class);
$app->get('/about', [AboutController::class, 'renderAbout']);
$app->get('/register', [AuthController::class, 'renderRegisterUser'])->add(GuestOnlyMiddleware::class);
$app->post('/register', [AuthController::class, 'registerUser'])->add(GuestOnlyMiddleware::class);
$app->get('/login', [AuthController::class, 'renderLoginUser'])->add(GuestOnlyMiddleware::class);
$app->post('/login', [AuthController::class, 'login'])->add(GuestOnlyMiddleware::class);
$app->get('/logout', [AuthController::class, 'logout'])->add(AuthRequiredMiddleware::class);
$app->get('/article', [ArticleController::class, 'renderCreateArticle'])->add(AuthRequiredMiddleware::class);
$app->post('/article', [ArticleController::class, 'createArticle'])->add(AuthRequiredMiddleware::class);
$app->get('/article/{article}', [ArticleController::class, 'renderEditArticle'])->add(AuthRequiredMiddleware::class);
$app->post('/article/{article}', [ArticleController::class, 'editArticle'])->add(AuthRequiredMiddleware::class);
$app->delete('/article/{article}', [ArticleController::class, 'deleteArticle'])->add(AuthRequiredMiddleware::class);

$app->addMiddleware(CsrfGuardMiddleware::class);
$app->addMiddleware(CsrfTokenMiddleware::class);
$app->addMiddleware(TemplateDataMiddleware::class);
$app->addMiddleware(ValidationExceptionMiddleware::class);
$app->addMiddleware(FlashMiddleware::class);
$app->addMiddleware(SessionMiddleware::class);

return $app;
