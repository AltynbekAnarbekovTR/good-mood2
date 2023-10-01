<?php

declare(strict_types=1);

use Framework\{TemplateEngine, Database, Container};
use App\Config\Paths;
use App\Services\{FormValidatorService, UploadFileService, EmailService, ImageService};
use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use App\Models\AuthCode;
return [
        TemplateEngine::class       => fn() => new TemplateEngine(Paths::VIEW),
        FormValidatorService::class => fn() => new FormValidatorService(),
        EmailService::class => fn() => new EmailService(),
        ImageService::class => fn() => new ImageService(),
        Database::class             => fn() => new Database(
                getenv('DRIVER'), [
                'host'   => getenv('HOST'),
                'port'   => getenv('PORT'),
                'dbname' => getenv('DBNAME'),
        ], getenv('USERNAME'), getenv('PASSWORD')
        ),
        User::class            => function (Container $container) {
          $db = $container->get(Database::class);
          return new User($db);
        },
        Article::class => function (Container $container) {
          $db = $container->get(Database::class);
          return new Article($db);
        },
        Comment::class => function (Container $container) {
          $db = $container->get(Database::class);

          return new Comment($db);
        },
        AuthCode::class   => function (Container $container) {
          $db = $container->get(Database::class);
          return new AuthCode($db);
        },
        UploadFileService::class   => function (Container $container) {
          $db = $container->get(Database::class);

          return new UploadFileService($db);
        }

];
