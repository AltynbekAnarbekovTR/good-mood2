<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config\Paths;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Framework\TemplateEngine;
use App\Services\{ImageService, UploadFileService, FormValidatorService};

class ArticleController
{
  public function __construct(
          private TemplateEngine $view,
          private FormValidatorService $formValidatorService,
          private UploadFileService $uploadFileService,
          private Article $articleModel,
          private Comment $commentModel,
          private User $userModel,
          private ImageService $imageService
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
    $articleImages = $this->imageService->createB64ImageArray($articles);
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
                    'articleImages'    => $articleImages,
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
    $this->formValidatorService->validateArticle($_POST);
    $this->articleModel->update($_POST,(int) $params['article']);
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
    $articleImage = $this->imageService->createB64Image($article);
    $comments = $this->commentModel->getCommentsOfArticle(
            $article->getId()
    );
    $userAvatars = [];
    foreach ($comments as $comment) {
      $user = $this->userModel->getUserById($comment->getUserId());
      if($user->getStorageFilename()) {
        $userAvatar = $this->imageService->createB64Image($user);
        $userAvatars[$user->getId()] = $userAvatar;
      }
    }
    $this->view->render(
            'articles/article.php',
            [
                    'article' => $article,
                    'articleImage' => $articleImage,
                    'comments' => $comments,
                    'userAvatars' => $userAvatars
            ]
    );
  }

}
