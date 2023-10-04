<?php

declare(strict_types=1);

use Framework\{TemplateEngine, Database, Container};
use App\Config\Paths;
use App\Services\{FormValidatorService, UploadFileService, EmailService, ImageService, ErrorMessagesService};
use App\Models\{User, Article, Comment, AuthCode};
use App\Views\{ArticlesView, AboutView, AuthView, HomeView, ProfileView};
return [
        TemplateEngine::class       => fn() => new TemplateEngine(Paths::VIEW),
        FormValidatorService::class => fn() => new FormValidatorService(),
        EmailService::class => fn() => new EmailService(),
        ImageService::class => fn() => new ImageService(),
        ErrorMessagesService::class => fn() => new ErrorMessagesService(),
        ArticlesView::class => function (Container $container) {
          $templateEngine = $container->get(TemplateEngine::class);
          return new ArticlesView($templateEngine);
        },
        AboutView::class => function (Container $container) {
          $templateEngine = $container->get(TemplateEngine::class);
          return new AboutView($templateEngine);
        },
        AuthView::class => function (Container $container) {
          $templateEngine = $container->get(TemplateEngine::class);
          return new AuthView($templateEngine);
        },
        HomeView::class => function (Container $container) {
          $templateEngine = $container->get(TemplateEngine::class);
          return new HomeView($templateEngine);
        },
        ProfileView::class => function (Container $container) {
          $templateEngine = $container->get(TemplateEngine::class);
          return new ProfileView($templateEngine);
        },
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
