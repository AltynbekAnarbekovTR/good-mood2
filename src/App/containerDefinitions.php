<?php

declare(strict_types=1);

use Framework\{TemplateEngine, Database, Container};
use App\Config\Paths;
use App\Services\{FormValidatorService, UploadFileService, EmailService, ImageService, ErrorMessagesService};
use App\Models\{User, Article, Comment, AuthCode, Category};
use App\Views\{ArticlesView, UsersView, AboutView, AuthView, LoginView, HeroArticleView, FormView, ProfileView, LayoutView, ArticlesGridView};
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
        FormView::class => function (Container $container) {
          $templateEngine = $container->get(TemplateEngine::class);
          return new FormView($templateEngine);
        },
        AboutView::class => function (Container $container) {
          $templateEngine = $container->get(TemplateEngine::class);
          return new AboutView($templateEngine);
        },
        AuthView::class => function (Container $container) {
          $templateEngine = $container->get(TemplateEngine::class);
          return new AuthView($templateEngine);
        },
        ProfileView::class => function (Container $container) {
          $templateEngine = $container->get(TemplateEngine::class);
          return new ProfileView($templateEngine);
        },
        LayoutView::class => function (Container $container) {
          $templateEngine = $container->get(TemplateEngine::class);
          return new LayoutView($templateEngine);
        },
        HeroArticleView::class => function (Container $container) {
          $templateEngine = $container->get(TemplateEngine::class);
          return new HeroArticleView($templateEngine);
        },
        ArticlesGridView::class => function (Container $container) {
          $templateEngine = $container->get(TemplateEngine::class);
          return new ArticlesGridView($templateEngine);
        },
        LoginView::class => function (Container $container) {
          $templateEngine = $container->get(TemplateEngine::class);
          return new LoginView($templateEngine);
        },
        UsersView::class => function (Container $container) {
          $templateEngine = $container->get(TemplateEngine::class);
          return new UsersView($templateEngine);
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
        Category::class            => function (Container $container) {
          $db = $container->get(Database::class);
          return new Category($db);
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
