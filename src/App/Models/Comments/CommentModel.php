<?php

declare(strict_types=1);

namespace App\Models\Comments;

use App\Config\Paths;
use Framework\Model;

class CommentModel extends Model
{
  public int $id;
  public string $commentText;
  public string $createdAt;
  public string $updatedAt;
  public int $userId;
  public int $articleId;

  public function create(array $formData, int $articleId)
  {
    $this->db->query(
            "INSERT INTO comments(comment_text, username, user_id, article_id) 
            VALUES(:comment_text, :username, :user_id, :article_id)",
            [
                    'comment_text' => $formData['comment_text'],
                    'username'     => $_SESSION['user']['username'],
                    'user_id'      => $_SESSION['user']['id'],
                    'article_id'   => $articleId,
            ]
    );
  }

  public function edit(array $formData, int $commentId)
  {
    $this->db->query(
            "UPDATE comments
      SET comment_text = :comment_text
      WHERE id = :id",
            [
                    'comment_text' => $formData['comment_text'],
                    'id'           => $commentId,
            ]
    );
  }

  public function delete(int $commentId)
  {
    $this->db->query(
            "DELETE FROM comments WHERE id = :id",
            [
                    'id' => $commentId,
            ]
    );
  }

  public function getCommentsOfArticle(int $articleId): array
  {
    return $this->db->query(
            "SELECT * FROM comments WHERE article_id = :article_id",
            ['article_id' => $articleId]
    )->findAll();
  }

}
