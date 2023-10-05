<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Views\{FormView, LayoutView, ArticlesView};
use App\Services\{ErrorMessagesService, ImageService, UploadFileService, FormValidatorService};

class ArticleController
{
  public function __construct(
          private ArticlesView $articlesView,
          private FormView $formView,
          private LayoutView $layoutView,
          private FormValidatorService $formValidatorService,
          private UploadFileService $uploadFileService,
          private Article $articleModel,
          private Comment $commentModel,
          private User $userModel,
          private ImageService $imageService,
          private ErrorMessagesService $errorMessagesService
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
    $manageArticlesTemplate = $this->articlesView->getManageArticlesTemplate(
            [
                    'articles'          => $articles,
                    'articleImages'     => $articleImages,
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
    $this->layoutView->renderPage($manageArticlesTemplate);
  }

  public function renderCreateArticle()
  {
    $createArticleTemplate = $this->articlesView->getCreateArticleTemplate();
    $this->layoutView->renderPage($createArticleTemplate);
  }

  public function createArticle()
  {
    $this->formValidatorService->addRulesToField('title', ['required', 'lengthMax:100']);
    $this->formValidatorService->addRulesToField('description', ['required', 'lengthMax:500']);
    $this->formValidatorService->addRulesToField('text', ['required', 'lengthMax:2000']);
    $errors = [];
    $errors += $this->formValidatorService->validate($_POST);
    $errors += $this->uploadFileService->checkUploadedImage($_FILES['cover']);
    [$newFilename, $fileIsUploaded] = $this->uploadFileService->uploadImageToStorage($_FILES['cover']);
    if (!$fileIsUploaded) {
      $errors += ['cover' => ['Failed to upload file']];
    }
    if (count($errors)) {
      $this->errorMessagesService->setErrorMessage($errors);
    }
    $this->articleModel->create($_POST, $_FILES['cover'], $newFilename);
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
    $editArticleTemplate = $this->articlesView->getEditArticleTemplate(
            [
                    'article' => $article,
            ]
    );
    $this->layoutView->renderPage($editArticleTemplate);
  }

  public function editArticle(array $params)
  {
    $this->formValidatorService->addRulesToField('title', ['required', 'lengthMax:100']);
    $this->formValidatorService->addRulesToField('description', ['required', 'lengthMax:500']);
    $this->formValidatorService->addRulesToField('text', ['required', 'lengthMax:2000']);
    $errors = $this->formValidatorService->validate($_POST);
    if (count($errors)) {
      $this->errorMessagesService->setErrorMessage($errors);
    }
    $this->articleModel->update($_POST, (int)$params['article']);
    redirectTo('manage-articles');
  }

  public function deleteArticle(array $params)
  {
    $this->articleModel->delete((int)$params['article']);

    redirectTo('/manage-articles');
  }

  public function renderReadArticle($params)
  {
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
      if ($user->getStorageFilename()) {
        $userAvatar = $this->imageService->createB64Image($user);
        $userAvatars[$user->getId()] = $userAvatar;
      }
    }
    $readArticleTemplate = $this->articlesView->getReadArticleTemplate(
            [
                    'article'      => $article,
                    'articleImage' => $articleImage,
                    'comments'     => $comments,
                    'userAvatars'  => $userAvatars,
            ]
    );
    $this->layoutView->renderPage($readArticleTemplate);
  }

}
