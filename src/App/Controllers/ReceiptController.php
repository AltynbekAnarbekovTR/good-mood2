<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ArticleService, ReceiptService};

class ReceiptController
{
  public function __construct(
    private TemplateEngine $view,
    private ArticleService $articleService,
    private ReceiptService $receiptService
  ) {
  }

  public function uploadView(array $params)
  {
    $article = $this->articleService->getUserArticle($params['article']);

    if (!$article) {
      redirectTo("/");
    }

    echo $this->view->render("receipts/create.php");
  }

  public function upload(array $params)
  {

    $article = $this->articleService->getUserArticle($params['article']);

    if (!$article) {
      redirectTo("/");
    }

    $receiptFile = $_FILES['receipt'] ?? null;
      die();
    $this->receiptService->validateFile($receiptFile);

    $this->receiptService->upload($receiptFile, $article['id']);

    redirectTo("/");
  }

  public function download(array $params)
  {
    $article = $this->articleService->getUserArticle(
      $params['article']
    );

    if (empty($article)) {
      redirectTo('/');
    }

    $receipt = $this->receiptService->getReceipt($params['receipt']);

    if (empty($receipt)) {
      redirectTo('/');
    }

    if ($receipt['article_id'] !== $article['id']) {
      redirectTo('/');
    }

    $this->receiptService->read($receipt);
  }

  public function delete(array $params)
  {
    $article = $this->articleService->getUserArticle(
      $params['article']
    );

    if (empty($article)) {
      redirectTo('/');
    }

    $receipt = $this->receiptService->getReceipt($params['receipt']);

    if (empty($receipt)) {
      redirectTo('/');
    }

    if ($receipt['article_id'] !== $article['id']) {
      redirectTo('/');
    }

    $this->receiptService->delete($receipt);

    redirectTo('/');
  }
}
