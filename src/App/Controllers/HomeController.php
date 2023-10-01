<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ImageService;
use Framework\TemplateEngine;
use App\Models\Article;

class HomeController
{
  public function __construct(
          private TemplateEngine $view,
          private Article $articleModel,
          private ImageService $imageService
  ) {
  }

  public function renderHome()
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
            'index.php',
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
}
