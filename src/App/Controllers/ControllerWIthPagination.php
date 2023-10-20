<?php

declare(strict_types=1);

namespace App\Controllers;

abstract class ControllerWIthPagination
{
  public function prepareDataWithPagination(string $dataName, $functionToGetAllData, array $params = [])
  {
    $page = $_GET['p'] ?? 1;
    $sortyByDate = $_GET['date'] ?? '';
    $page = (int)$page;
    $length = 3;
    $offset = ($page - 1) * $length;
    $searchTerm = $_GET['s'] ?? null;
    [$objects, $count] = $functionToGetAllData($length, $offset, $sortyByDate, ...$params);
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
    return
            [
                    $dataName           => $objects,
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
}
