<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Articles\ArticleModel;
use Framework\TemplateEngine;
use App\Services\{UploadFileService, FormValidatorService};

class ArticleController
{
  public function __construct(
          private TemplateEngine $view,
          private FormValidatorService $formValidatorService,
          private UploadFileService $uploadFileService,
          private ArticleModel $articleModel
  ) {
  }

  public function renderManageArticles()
  {
    $page = $_GET['p'] ?? 1;
    $page = (int)$page;
    $length = 3;
    $offset = ($page - 1) * $length;
    $searchTerm = $_GET['s'] ?? null;
    [$articles, $count] = $this->articleModel->getAllArticles(
            $length,
            $offset
    );
    $lastPage = ceil($count / $length);
    $pages = $lastPage ? range(1, $lastPage) : [];

    $pageLinks = array_map(
            fn($pageNum) => http_build_query(
                    [
                            'p' => $pageNum,
                            's' => $searchTerm,
                    ]
            ),
            $pages
    );
    $this->view->render(
            "articles/manageArticles.php",
            [
                    'articles'          => $articles,
                    'currentPage'       => $page,
                    'previousPageQuery' => http_build_query(
                            [
                                    'p' => $page - 1,
                                    's' => $searchTerm,
                            ]
                    ),
                    'lastPage'          => $lastPage,
                    'nextPageQuery'     => http_build_query(
                            [
                                    'p' => $page + 1,
                                    's' => $searchTerm,
                            ]
                    ),
                    'pageLinks'         => $pageLinks,
                    'searchTerm'        => $searchTerm,
            ]
    );
  }

  public function renderCreateArticle()
  {
    $this->view->render("articles/create.php");
  }

  public function createArticle()
  {
    $this->formValidatorService->validateArticle($_POST);
    $this->uploadFileService->checkUploadIsImage($_FILES);
    $this->articleModel->create($_POST);
    redirectTo('/manageArticles');
  }

  public function renderEditArticle(array $params)
  {
    $article = $this->articleModel->getUserArticle(
            $params['article']
    );

    if (!$article) {
      redirectTo('/manageArticles');
    }

    $this->view->render(
            'articles/edit.php',
            [
                    'article' => $article,
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

    $this->formValidatorService->validateArticle($_POST);

    $this->articleModel->update($_POST, $article['id']);

    redirectTo('manageArticles');
  }

  public function deleteArticle(array $params)
  {
    $this->articleModel->delete((int)$params['article']);

    redirectTo('/manageArticles');
  }

  public function renderReadArticle($params) {
    $article = $this->articleModel->getUserArticle(
            $params['article']
    );
    $this->view->render(
            'articles/article.php',
            [
                    'article' => $article,
            ]
    );
  }

}
