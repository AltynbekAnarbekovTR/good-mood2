<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Category;
use App\Services\ImageService;
use App\Views\ArticlesGridView;
use App\Views\HeroArticleView;
use App\Models\Article;
use App\Views\LayoutView;

class HomeController
{
  public function __construct(
          private HeroArticleView $heroArticleView,
          private ArticlesGridView $articlesGridView,
          private LayoutView $layoutView,
          private Article $articleModel,
          private Category $categoryModel,
          private ImageService $imageService
  ) {
  }

  public function renderArticlesGrid() {
    $category = $_GET['category'] ?? '';
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
    return $this->articlesGridView->renderArticlesGrid(
            [
                    'sectionTitle'      => $_GET['category'] ?? 'Latest Articles',
                    'allCategories' => $this->categoryModel->getCategories(),
                    'articles'          => $articles,
                    'articlesCategories' => $articlesCategories,
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
  }

  public function renderHome()
  {
    $articlesGrid = $this->renderArticlesGrid();
    $homePageMainArticle = $this->articleModel->getMainArticle('home');
    if($homePageMainArticle) {
      $homePageMainArticleImage = $this->imageService->createB64Image($homePageMainArticle);
      $homePageMainArticleCategories = $this->categoryModel->getArticleCategories($homePageMainArticle->getId());
      $heroArticle = $this->heroArticleView->renderHeroArticle(
              ['mainArticle' => $homePageMainArticle, 'mainArticleImage' => $homePageMainArticleImage, 'homePageMainArticleCategories' => $homePageMainArticleCategories]
      );
    } else $heroArticle = '';
    $homePage = $heroArticle.$articlesGrid;
    $this->layoutView->renderPage($homePage);
  }
}
