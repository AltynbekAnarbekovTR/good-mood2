<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config\Paths;
use Framework\TemplateEngine;
use App\Services\ArticleService;

class HomeController
{
    public function __construct(
        private TemplateEngine $view,
        private ArticleService $articleService
    ) {
    }

    public function home()
    {
        $page = $_GET['p'] ?? 1;
        $page = (int)$page;
        $length = 3;
        $offset = ($page - 1) * $length;
        $searchTerm = $_GET['s'] ?? null;

        [$articles, $count] = $this->articleService->getUserArticles(
            $length,
            $offset
        );


        $lastPage = ceil($count / $length);
        $pages = $lastPage ? range(1, $lastPage) : [];

        $pageLinks = array_map(
            fn($pageNum) => http_build_query(
                [
                    'p' => $pageNum,
                    's' => $searchTerm
                ]
            ),
            $pages
        );

        echo $this->view->render(
            "index.php",
            [
                'articles' => $articles,
                'currentPage' => $page,
                'previousPageQuery' => http_build_query(
                    [
                        'p' => $page - 1,
                        's' => $searchTerm
                    ]
                ),
                'lastPage' => $lastPage,
                'nextPageQuery' => http_build_query(
                    [
                        'p' => $page + 1,
                        's' => $searchTerm
                    ]
                ),
                'pageLinks' => $pageLinks,
                'searchTerm' => $searchTerm
            ]
        );
    }
}
