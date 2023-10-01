<?php

declare(strict_types=1);

namespace App\Models;

use Framework\ActiveRecordEntity;

class Comment extends ActiveRecordEntity
{
  protected int $id;
  protected string $username;
  protected string $commentText;
  protected string $createdAt;
  protected string $updatedAt;
  protected int $userId;
  protected int $articleId;

  public function getId(): int
  {
    return $this->id;
  }
  public function getUsername(): string
  {
    return $this->username;
  }
  public function getCommentText(): string
  {
    return $this->commentText;
  }
  public function getCreatedAt(): string
  {
    return $this->createdAt;
  }
  public function getUserId(): int
  {
    return $this->userId;
  }

  public function create(array $formData, int $articleId)
  {
    $this->db->query(
            "INSERT INTO comments(comment_text, username, user_id, article_id) 
            VALUES(:comment_text, :username, :user_id, :article_id)",
            [
                    'comment_text' => $formData['comment_text'],
                    'username'     => $_SESSION['user']['username'],
                    'user_id'      => $_SESSION['user']['userId'],
                    'article_id'   => $articleId
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
    )->findAll(Comment::class);
  }

}
