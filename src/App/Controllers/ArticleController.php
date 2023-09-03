<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorService, ArticleService};

class ArticleController
{
    public function __construct(
        private TemplateEngine $view,
        private ValidatorService $validatorService,
        private ArticleService $articleService
    ) {
    }

    public function renderCreateArticle()
    {
        $this->view->render("articles/create.php");
    }

    public function createArticle()
    {
        $this->validatorService->validateArticle($_POST);
        $this->articleService->create($_POST);

        redirectTo('/');
    }

    public function renderEditArticle(array $params)
    {
        $article = $this->articleService->getUserArticle(
            $params['article']
        );

        if (!$article) {
            redirectTo('/');
        }

        $this->view->render(
            'articles/edit.php',
            [
                'article' => $article
            ]
        );
    }

    public function editArticle(array $params)
    {
        $article = $this->articleService->getUserArticle(
            $params['article']
        );

        if (!$article) {
            redirectTo('/');
        }

        $this->validatorService->validateArticle($_POST);

        $this->articleService->update($_POST, $article['id']);

        redirectTo($_SERVER['HTTP_REFERER']);
    }

    public function deleteArticle(array $params)
    {
        $this->articleService->delete((int)$params['article']);

        redirectTo('/');
    }


}
