<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Comment;

class CommentsController
{
  public function __construct(
          private Comment $commentModel
  ) {
  }

  public function createComment(array $params)
  {
    $this->commentModel->create($_POST, (int) $params['article']);
    redirectTo($_SERVER['HTTP_REFERER']);
  }

  public function editComment(array $params) {
    $this->commentModel->edit($_POST, (int) $params['commentId']);
  }

  public function deleteComment(array $params) {
    $this->commentModel->delete((int) $params['commentId']);
    redirectTo($_SERVER['HTTP_REFERER']);
  }
}
