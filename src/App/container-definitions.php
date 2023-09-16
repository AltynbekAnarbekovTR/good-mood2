<?php

declare(strict_types=1);

use Framework\{TemplateEngine, Database, Container};
use App\Config\Paths;
use App\Services\{FormValidatorService, UploadFileService, EmailService};
use App\Models\Users\UserModel;
use App\Models\Articles\ArticleModel;
return [
        TemplateEngine::class       => fn() => new TemplateEngine(Paths::VIEW),
        FormValidatorService::class => fn() => new FormValidatorService(),
        EmailService::class => fn() => new EmailService(),
        Database::class             => fn() => new Database(
                'mysql', [
                'host'   => 'localhost',
                'port'   => 3306,
                'dbname' => 'good_mood2',
        ], 'root', 'root'
        ),
        UserModel::class            => function (Container $container) {
          $db = $container->get(Database::class);

          return new UserModel($db);
        },
        ArticleModel::class   => function (Container $container) {
          $db = $container->get(Database::class);

          return new ArticleModel($db);
        },
        UploadFileService::class   => function (Container $container) {
          $db = $container->get(Database::class);

          return new UploadFileService($db);
        },

];
