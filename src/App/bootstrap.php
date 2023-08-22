<?php

declare(strict_types=1);

if (!defined('AUTOLOADER_INITIALIZED')) {
    require_once dirname(__DIR__) . '../../autoload.php';
}

use Framework\App;
use App\Controllers\HomeController;

$app = new App(Paths::SOURCE . "App/container-definitions.php");

$app->get('/', [HomeController::class, 'home']);

$app->addMiddleware(TemplateDataMiddleware::class);

return $app;
