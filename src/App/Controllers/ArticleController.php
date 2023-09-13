<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Articles\ArticleModel;
use Framework\TemplateEngine;
use App\Services\{ValidatorService};

class ArticleController
{
    public function __construct(
        private TemplateEngine $view,
        private ValidatorService $validatorService,
        private ArticleModel $articleModel
    ) {
    }

    public function renderCreateArticle()
    {
        $this->view->render("articles/create.php");
    }

    public function createArticle()
    {
        $this->validatorService->validateArticle($_POST);
        $this->articleModel->create($_POST);

        redirectTo('/');
    }

    public function renderEditArticle(array $params)
    {
        $article = $this->articleModel->getUserArticle(
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
        $article = $this->articleModel->getUserArticle(
            $params['article']
        );

        if (!$article) {
            redirectTo('/');
        }

        $this->validatorService->validateArticle($_POST);

        $this->articleModel->update($_POST, $article['id']);

        redirectTo($_SERVER['HTTP_REFERER']);
    }

    public function deleteArticle(array $params)
    {
        $this->articleModel->delete((int)$params['article']);

        redirectTo('/');
    }


}
