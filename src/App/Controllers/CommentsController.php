<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config\Paths;
use App\Models\Articles\ArticleModel;
use App\Models\Comments\CommentModel;
use Framework\TemplateEngine;
use App\Services\{UploadFileService, FormValidatorService};

class CommentsController
{
  public function __construct(
          private CommentModel $commentModel
  ) {
  }

  public function createComment(array $params)
  {
    $this->commentModel->create($_POST, (int) $params['article']);
    redirectTo($_SERVER['HTTP_REFERER']);
  }
}
