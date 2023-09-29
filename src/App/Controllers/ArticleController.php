<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config\Paths;
use App\Models\Articles\ArticleModel;
use App\Models\Comments\CommentModel;
use App\Models\Users\UserModel;
use Framework\TemplateEngine;
use App\Services\{UploadFileService, FormValidatorService};

class ArticleController
{
  public function __construct(
          private TemplateEngine $view,
          private FormValidatorService $formValidatorService,
          private UploadFileService $uploadFileService,
          private ArticleModel $articleModel,
          private CommentModel $commentModel,
          private UserModel $userModel
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
    $articles = $this->articleModel->attachImagesToArticlesArray($articles);
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
    $this->formValidatorService->addRulesToField('titleRules', ['required', 'lengthMax:100']);
    $this->formValidatorService->addRulesToField('descriptionRules', ['required', 'lengthMax:500']);
    $this->formValidatorService->addRulesToField('textRules', ['required', 'lengthMax:2000']);
    $this->formValidatorService->validateArticle($_POST);
    if ($_FILES['cover']) {
      $this->uploadFileService->checkUploadIsImage($_FILES['cover']);
    }
    $this->articleModel->create($_POST, $_FILES['cover']);
    redirectTo('/manage-articles');
  }

  public function renderEditArticle(array $params)
  {
    $article = $this->articleModel->getArticleById(
            $params['article']
    );

    if (!$article) {
      redirectTo('/manage-articles');
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
    $article = $this->articleModel->getArticleById(
            $params['article']
    );
    if (!$article) {
      redirectTo('/');
    }
    $this->formValidatorService->validateArticle($_POST);
    $this->articleModel->update($_POST, $article['id']);
    redirectTo('manage-articles');
  }

  public function deleteArticle(array $params)
  {
    $this->articleModel->delete((int)$params['article']);

    redirectTo('/manage-articles');
  }

  public function renderReadArticle($params) {
    $article = $this->articleModel->getArticleById(
            $params['article']
    );
    $article = $this->articleModel->attachImageToArticle($article);
    $comments = $this->commentModel->getCommentsOfArticle(
            $article['id']
    );
    foreach ($comments as &$comment) {
      $user = $this->userModel->getUserById($comment['user_id']);
      $filename = $user['storage_filename'];
      $fileDir = Paths::STORAGE_UPLOADS;
      $file = $fileDir.DIRECTORY_SEPARATOR.$filename;
      if (file_exists($file)) {
        $b64image = base64_encode(file_get_contents($file));
        $comment['b64image'] = $b64image;
      }
    }
    $this->view->render(
            'articles/article.php',
            [
                    'article' => $article,
                    'comments' => $comments
            ]
    );
  }

}
