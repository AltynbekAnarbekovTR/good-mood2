<?php

declare(strict_types=1);

use Framework\{TemplateEngine, Database, Container};
use App\Config\Paths;
use App\Services\{
  ValidatorService,
  UserService,
  ArticleService,
  ReceiptService
};


return [
  TemplateEngine::class => fn () => new TemplateEngine(Paths::VIEW),
  ValidatorService::class => fn () => new ValidatorService(),
  Database::class => fn () => new Database(  'mysql', [
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'good_mood2'
], 'root', 'root'),
  UserService::class => function (Container $container) {
    $db = $container->get(Database::class);

    return new UserService($db);
  },
  ArticleService::class => function (Container $container) {
    $db = $container->get(Database::class);

    return new ArticleService($db);
  },
  ReceiptService::class => function (Container $container) {
    $db = $container->get(Database::class);

    return new ReceiptService($db);
  }
];
