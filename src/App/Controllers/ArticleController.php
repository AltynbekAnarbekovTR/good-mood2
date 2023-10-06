<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use App\Views\{ LayoutView, ArticlesView};
use App\Services\{ErrorMessagesService, ImageService, UploadFileService, FormValidatorService};

class ArticleController
{
  public function __construct(
          private ArticlesView $articlesView,
          private LayoutView $layoutView,
          private FormValidatorService $formValidatorService,
          private UploadFileService $uploadFileService,
          private Article $articleModel,
          private Category $categoryModel,
          private Comment $commentModel,
          private User $userModel,
          private ImageService $imageService,
          private ErrorMessagesService $errorMessagesService
  ) {
  }

  public function setMainArticle($params) {
    $this->articleModel->setMainFor((int) $params['article'], 'home');
    redirectTo('/manage-articles');
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
    $homePageMainArticle = $this->articleModel->getMainArticle('home');
    if($homePageMainArticle) {
      $homePageMainArticleImage = $this->imageService->createB64Image($homePageMainArticle);
    } else $homePageMainArticleImage = null;
    $manageArticlesTemplate = $this->articlesView->getManageArticlesTemplate(
            [
                    'homePageMainArticle' => $homePageMainArticle,
                    'homePageMainArticleImage' => $homePageMainArticleImage,
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
    $categories = $this->categoryModel->getCategories();
    $createArticleTemplate = $this->articlesView->getCreateArticleTemplate(['categories' => $categories]);
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
    $categories = $this->categoryModel->getCategories();
    $article = $this->articleModel->getArticleById(
            $params['article']
    );
    if (!$article) {
      redirectTo('/manage-articles');
    }
    $editArticleTemplate = $this->articlesView->getEditArticleTemplate(
            [
                    'article' => $article,
                    'categories' => $categories
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
