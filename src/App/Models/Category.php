<?php

declare(strict_types=1);

namespace App\Models;

use Framework\ActiveRecordEntity;

class Category extends ActiveRecordEntity
{
  protected int $id;
  protected string $title;

  public function getId(): int
  {
    return $this->id;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function getCategories(): array
  {
    return $this->db->query(
            "SELECT *
      FROM categories"
    )->findAll(Category::class);
  }

  function getArticleCategories($articleId) {
    return $categories =  $this->db->query(
            "SELECT c.title
                FROM categories c
                JOIN article_category ac ON c.id = ac.category_id
                WHERE ac.article_id = :article_id ",
            ['article_id' => $articleId]
    )->findAllColumn();
  }
}
