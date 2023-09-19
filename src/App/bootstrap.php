<?php

declare(strict_types=1);

if (!defined('AUTOLOADER_INITIALIZED')) {
  require __DIR__ . '/../../autoload.php';
}

require __DIR__ . '/utilityFunctions.php';
require __DIR__ . '/../../env.php';
if (getenv('APP_MODE') === 'development') {
  require __DIR__ . '/devOnlyFunctions.php';
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
        AdminOnlyMiddleware
};
use App\Controllers\{
        HomeController,
        AboutController,
        AuthController,
        ArticleController,
        CommentsController,
        ProfileController
};

$app = new App(Paths::SOURCE."App/container-definitions.php");

$app->get('/', [HomeController::class, 'renderHome']);
$app->get('/about', [AboutController::class, 'renderAbout']);
$app->get('/register', [AuthController::class, 'renderRegisterUser'])->add(GuestOnlyMiddleware::class);
$app->post('/register', [AuthController::class, 'registerUser'])->add(GuestOnlyMiddleware::class);
$app->get('/login', [AuthController::class, 'renderLoginUser'])->add(GuestOnlyMiddleware::class);
$app->post('/login', [AuthController::class, 'login'])->add(GuestOnlyMiddleware::class);
$app->get('/logout', [AuthController::class, 'logout'])->add(AuthRequiredMiddleware::class);
$app->get('/manageArticles', [ArticleController::class, 'renderManageArticles'])->add(AdminOnlyMiddleware::class);
$app->get('/createArticle', [ArticleController::class, 'renderCreateArticle'])->add(AdminOnlyMiddleware::class);
$app->post('/createArticle', [ArticleController::class, 'createArticle'])->add(AdminOnlyMiddleware::class);
$app->get('/editArticle/{article}', [ArticleController::class, 'renderEditArticle'])->add(
        AdminOnlyMiddleware::class
);
$app->post('/editArticle/{article}', [ArticleController::class, 'editArticle'])->add(AdminOnlyMiddleware::class);
$app->delete('/deleteArticle/{article}', [ArticleController::class, 'deleteArticle'])->add(AdminOnlyMiddleware::class);
$app->get('/article/{article}', [ArticleController::class, 'renderReadArticle']);
$app->post('/article/{article}', [CommentsController::class, 'createComment'])->add(AuthRequiredMiddleware::class);;
$app->get('/verifyAccount', [AuthController::class, 'checkAccountVerification']);
$app->get('/profile', [ProfileController::class, 'renderProfile'])->add(AuthRequiredMiddleware::class);

$app->addMiddleware(CsrfGuardMiddleware::class);
$app->addMiddleware(CsrfTokenMiddleware::class);
$app->addMiddleware(TemplateDataMiddleware::class);
$app->addMiddleware(ValidationExceptionMiddleware::class);
$app->addMiddleware(FlashMiddleware::class);
$app->addMiddleware(SessionMiddleware::class);

return $app;
