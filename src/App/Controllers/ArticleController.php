<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use App\Views\{ArticlesGridView, HeroArticleView, LayoutView, ArticlesView};
use App\Services\{ErrorMessagesService, ImageService, UploadFileService, FormValidatorService};

class ArticleController
{
  public function __construct(
          private ArticlesView $articlesView,
          private LayoutView $layoutView,
          private ArticlesGridView $articlesGridView,
          private FormValidatorService $formValidatorService,
          private UploadFileService $uploadFileService,
          private Article $articleModel,
          private Category $categoryModel,
          private HeroArticleView $heroArticleView,
          private Comment $commentModel,
          private User $userModel,
          private ImageService $imageService,
          private ErrorMessagesService $errorMessagesService
  ) {
    $this->formValidatorService->addRulesToField('title', ['required', 'lengthMax:100']);
    $this->formValidatorService->addRulesToField('description', ['required', 'lengthMax:500']);
    $this->formValidatorService->addRulesToField('text', ['required', 'lengthMax:3000']);
  }

  public function prepareArticlesData($category = '', $provideAllCategories = false): array
  {
    $allCategories = null;
    if($provideAllCategories) {
      $allCategories = $this->categoryModel->getCategories();
    }
    $page = $_GET['p'] ?? 1;
    $page = (int)$page;
    $length = 3;
    $offset = ($page - 1) * $length;
    $searchTerm = $_GET['s'] ?? null;
    [$articles, $count] = $this->articleModel->getAllArticles(
            $length,
            $offset,
            $category
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
    $articleImages = $this->imageService->createB64ImageArray($articles);
    $articlesCategories = [];
    foreach ($articles as $article) {
      $categories = $this->categoryModel->getArticleCategories($article->getId());
      $articlesCategories[$article->getId()] = $categories;
    }
    return
            [
                    'sectionTitle'       => $category === '' ? 'Latest Articles' : $category,
                    'allCategories'      => $allCategories,
                    'articles'           => $articles,
                    'articlesCategories' => $articlesCategories,
                    'articleImages'      => $articleImages,
                    'currentPage'        => $page,
                    'previousPageQuery'  => http_build_query(
                            [
                                    'p' => $page - 1,
                                    's' => $searchTerm,
                            ]
                    ),
                    'lastPage'           => $lastPage,
                    'nextPageQuery'      => http_build_query(
                            [
                                    'p' => $page + 1,
                                    's' => $searchTerm,
                            ]
                    ),
                    'pageLinks'          => $pageLinks,
                    'searchTerm'         => $searchTerm,
            ]
    ;
  }

  public function prepareMainArticleData(): array|null
  {
    $homePageMainArticle = $this->articleModel->getMainArticle('home');
    if ($homePageMainArticle) {
      $homePageMainArticleImage = $this->imageService->createB64Image($homePageMainArticle);
      $homePageMainArticleCategories = $this->categoryModel->getArticleCategories($homePageMainArticle->getId());
      return
              [
                      'mainArticle'                   => $homePageMainArticle,
                      'mainArticleImage'              => $homePageMainArticleImage,
                      'mainArticleCategories' => $homePageMainArticleCategories,
              ];
    } else return null;
  }

  public function renderHome()
  {
    $category = $_GET['category'] ?? '';
    $dataForDisplayingArticles = $this->prepareArticlesData($category, true);
    $articlesGrid = $this->articlesGridView->renderArticlesGrid($dataForDisplayingArticles);
    $mainArticleData = $this->prepareMainArticleData();
    if(!$mainArticleData) {
      $heroArticle = '';
    } else {
      $heroArticle = $this->heroArticleView->renderHeroArticle(
              $mainArticleData
      );
    }
    $homePage = $heroArticle.$articlesGrid;
    $this->layoutView->renderPage($homePage);
  }

  public function renderArticlesByCategory($params)
  {
    $category = $params['category'] === 'all-articles' ? '' : $params['category'];
    $dataForDisplayingArticles = $this->prepareArticlesData($category);
    $articlesGrid = $this->articlesGridView->renderArticlesGrid($dataForDisplayingArticles);
    $this->layoutView->renderPage($articlesGrid);
}

  public function setMainArticle($params) {
    $this->articleModel->setMainFor((int) $params['article'], 'home');
    redirectTo('/manage-articles');
  }

  public function renderManageArticles()
  {
    $category = $_GET['category'] ?? '';
    $dataForDisplayingArticles = $this->prepareArticlesData($category, true);
    if($mainArticleData = $this->prepareMainArticleData()) {
      $dataForDisplayingArticles += $mainArticleData;
    };
    $manageArticlesTemplate = $this->articlesView->getManageArticlesTemplate(
            $dataForDisplayingArticles
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
    $createdArticleId = $this->articleModel->lastInsertId();
    if (isset($_POST['category'])) {
      $this->articleModel->addCategoriesToArticle($createdArticleId, $_POST['category']);
    }
    redirectTo('/manage-articles');
  }

  public function renderEditArticle(array $params)
  {
    $categories = $this->categoryModel->getCategories();
    $article = $this->articleModel->getArticleById(
            $params['article']
    );
    $articleCategories = $this->categoryModel->getArticleCategories($article->getId());
    if (!$article) {
      redirectTo('/manage-articles');
    }
    $editArticleTemplate = $this->articlesView->getEditArticleTemplate(
            [
                    'article' => $article,
                    'categories' => $categories,
                    'articleCategories' => $articleCategories
            ]
    );
    $this->layoutView->renderPage($editArticleTemplate);
  }

  public function editArticle(array $params)
  {
    $errors = $this->formValidatorService->validate($_POST);
    if(isset($_FILES['cover']) && $_FILES['cover']['name'] !== '') {
      $errors += $this->uploadFileService->checkUploadedImage($_FILES['cover']);
      [$newFilename, $fileIsUploaded] = $this->uploadFileService->uploadImageToStorage($_FILES['cover']);
      if (!$fileIsUploaded) {
        $errors += ['cover' => ['Failed to upload file']];
      }
    }
    if (count($errors)) {
      $this->errorMessagesService->setErrorMessage($errors);
    }
    $this->articleModel->update($_POST, (int)$params['article'], $_FILES['cover'], $newFilename);
    redirectTo('/manage-articles');
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
    $articleCategories = $this->categoryModel->getArticleCategories($article->getId());
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
                    'articleCategories' => $articleCategories,
                    'articleImage' => $articleImage,
                    'comments'     => $comments,
                    'userAvatars'  => $userAvatars,
            ]
    );
    $this->layoutView->renderPage($readArticleTemplate);
  }
}
