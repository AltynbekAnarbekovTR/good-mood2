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
    )->findAll(Article::class);
  }
}
