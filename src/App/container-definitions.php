<?php

declare(strict_types=1);

use Framework\{TemplateEngine, Database, Container};
use App\Config\Paths;
use App\Services\{ValidatorService};
use App\Models\Users\UserModel;
use App\Models\Articles\ArticleModel;
return [
        TemplateEngine::class   => fn() => new TemplateEngine(Paths::VIEW),
        ValidatorService::class => fn() => new ValidatorService(),
        Database::class         => fn() => new Database(
                'mysql', [
                'host'   => 'localhost',
                'port'   => 3306,
                'dbname' => 'good_mood2',
        ], 'root', 'root'
        ),
        UserModel::class      => function (Container $container) {
          $db = $container->get(Database::class);

          return new UserModel($db);
        },
        ArticleModel::class   => function (Container $container) {
          $db = $container->get(Database::class);

          return new ArticleModel($db);
        },
        ReceiptService::class   => function (Container $container) {
          $db = $container->get(Database::class);

          return new ReceiptService($db);
        },
];
