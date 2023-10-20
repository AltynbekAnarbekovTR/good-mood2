<?php

declare(strict_types=1);

if (!defined('AUTOLOADER_INITIALIZED')) {
  require __DIR__.'/../../autoload.php';
}

require __DIR__.'/utilityFunctions.php';
require __DIR__.'/../../env.php';
if (getenv('APP_MODE') === 'development') {
  require __DIR__.'/devOnlyFunctions.php';
}

use Framework\App;
use App\Config\Paths;
use App\Middleware\{TemplateDataMiddleware,
        AdminAndAuthorOnlyMiddleware,
        SessionMiddleware,
        FlashMiddleware,
        AuthRequiredMiddleware,
        GuestOnlyMiddleware,
        CsrfTokenMiddleware,
        CsrfGuardMiddleware,
        AdminOnlyMiddleware
};
use App\Controllers\{
        UsersController,
        AboutController,
        AuthController,
        ArticleController,
        CommentsController,
        ProfileController
};

$app = new App(Paths::SOURCE."App/containerDefinitions.php");

$app->get('/', [ArticleController::class, 'renderHome']);
$app->get('/about', [AboutController::class, 'renderAbout']);
$app->get('/register', [AuthController::class, 'renderRegisterUser'])->add(GuestOnlyMiddleware::class);
$app->post('/register', [AuthController::class, 'registerUser'])->add(GuestOnlyMiddleware::class);
$app->get('/login', [AuthController::class, 'renderLoginUser'])->add(GuestOnlyMiddleware::class);
$app->post('/login', [AuthController::class, 'login'])->add(GuestOnlyMiddleware::class);
$app->get('/restore-password', [AuthController::class, 'renderRestorePassword'])->add(GuestOnlyMiddleware::class);
$app->post('/restore-password', [AuthController::class, 'restorePassword'])->add(GuestOnlyMiddleware::class);
$app->get('/reset-password', [AuthController::class, 'renderResetPassword']);
$app->post('/reset-password', [AuthController::class, 'resetPassword']);
$app->get('/logout', [AuthController::class, 'logout'])->add(AuthRequiredMiddleware::class);
$app->get('/manage-articles', [ArticleController::class, 'renderManageArticles'])->add(
        AdminAndAuthorOnlyMiddleware::class
);
$app->get('/manage-users', [UsersController::class, 'renderManageUsers'])->add(AdminOnlyMiddleware::class);
$app->get('/edit-user/{user}', [UsersController::class, 'renderEditUser'])->add(AdminOnlyMiddleware::class);
$app->post('/edit-user/{user}', [UsersController::class, 'editUser'])->add(AdminOnlyMiddleware::class);
$app->delete('/delete-user/{user}', [UsersController::class, 'deleteUser'])->add(
        AdminOnlyMiddleware::class
);
$app->get('/create-article', [ArticleController::class, 'renderCreateArticle'])->add(
        AdminAndAuthorOnlyMiddleware::class
);
$app->post('/create-article', [ArticleController::class, 'createArticle'])->add(AdminAndAuthorOnlyMiddleware::class);
$app->get('/edit-article/{article}', [ArticleController::class, 'renderEditArticle'])->add(
        AdminAndAuthorOnlyMiddleware::class
);
$app->post('/edit-article/{article}', [ArticleController::class, 'editArticle'])->add(
        AdminAndAuthorOnlyMiddleware::class
);
$app->delete('/delete-article/{article}', [ArticleController::class, 'deleteArticle'])->add(
        AdminAndAuthorOnlyMiddleware::class
);
$app->post('/set-main-article/{article}', [ArticleController::class, 'setMainArticle'])->add(
        AdminOnlyMiddleware::class
);
$app->get('/article/{article}', [ArticleController::class, 'renderReadArticle']);
$app->get('/category/{category}', [ArticleController::class, 'renderArticlesByCategory']);
$app->post('/article/{article}', [CommentsController::class, 'createComment'])->add(AuthRequiredMiddleware::class);;
$app->get('/verify-account', [AuthController::class, 'checkAccountVerification']);
$app->get('/profile', [ProfileController::class, 'renderProfile'])->add(AuthRequiredMiddleware::class);
$app->post('/edit-comment/{commentId}', [CommentsController::class, 'editComment'])->add(AuthRequiredMiddleware::class);
$app->get('/delete-comment/{commentId}', [CommentsController::class, 'deleteComment'])->add(
        AuthRequiredMiddleware::class
);
$app->post('/profile', [ProfileController::class, 'changeAvatar'])->add(AuthRequiredMiddleware::class);
$app->get('/profile/change-username', [ProfileController::class, 'renderChangeUsername'])->add(
        AuthRequiredMiddleware::class
);
$app->post('/profile/change-username', [ProfileController::class, 'changeUsername'])->add(
        AuthRequiredMiddleware::class
);
$app->get('/profile/change-email', [ProfileController::class, 'renderChangeEmail'])->add(AuthRequiredMiddleware::class);
$app->post('/profile/change-email', [ProfileController::class, 'changeEmail'])->add(AuthRequiredMiddleware::class);
$app->get('/verify-email-change', [ProfileController::class, 'verifyEmailChange']);

$app->addMiddleware(CsrfGuardMiddleware::class);
$app->addMiddleware(CsrfTokenMiddleware::class);
$app->addMiddleware(TemplateDataMiddleware::class);
$app->addMiddleware(FlashMiddleware::class);
$app->addMiddleware(SessionMiddleware::class);

return $app;
