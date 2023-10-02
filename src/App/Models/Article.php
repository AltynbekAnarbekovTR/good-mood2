<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Exceptions\ValidationException;
use App\Config\Paths;
use Framework\ActiveRecordEntity;

class Article extends ActiveRecordEntity
{
  protected int $id;
  protected string $title;
  protected string $description;
  protected string $articleText;
  protected string $createdAt;
  protected string $updatedAt;
  protected int $userId;
  protected string $originalFilename;
  protected string $storageFilename;
  protected string $mediaType;

  public function getId(): int
  {
    return $this->id;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function getArticleText(): string
  {
    return $this->articleText;
  }

  public function getCreatedAt(): string
  {
    return $this->createdAt;
  }

  public function getUserId(): int
  {
    return $this->userId;
  }

  public function getOriginalFilename(): string
  {
    return $this->originalFilename;
  }

  public function getStorageFilename(): string
  {
    return $this->storageFilename;
  }

  public function getMediaType(): string
  {
    return $this->mediaType;
  }

  public function create(array $formData, $coverImage = null, $newFilename = null)
  {
    $this->db->query(
            "INSERT INTO articles(user_id, title, description, article_text,original_filename, storage_filename, media_type) 
            VALUES(:user_id, :title, :description, :article_text, :original_filename, :storage_filename, :media_type)",
            [
                    'user_id'           => $_SESSION['user']['userId'],
                    'title'             => $formData['title'],
                    'description'       => $formData['description'],
                    'article_text'      => $formData['text'],
                    'original_filename' => $coverImage['name'],
                    'storage_filename'  => $newFilename,
                    'media_type'        => $coverImage['type'],
            ]
    );
  }

  public function getAllArticles(int $length = 3, int $offset = 0): array
  {
    $searchTerm = addcslashes($_GET['s'] ?? '', '%_');
    $params = [

            'title' => "%{$searchTerm}%",
    ];
    $articles = $this->db->query(
            "SELECT *
      FROM articles 
      WHERE title LIKE :title
      LIMIT {$length} OFFSET {$offset}",
            $params
    )->findAll(Article::class);

    $articleCount = $this->db->query(
            "SELECT COUNT(*)
      FROM articles 
      WHERE title LIKE :title",
            $params
    )->count();

    return [$articles, $articleCount];
  }

  public function getArticlesOfAuthor(int $length = 3, int $offset = 0): array
  {
    $searchTerm = addcslashes($_GET['s'] ?? '', '%_');
    $params = [
            'user_id'     => $_SESSION['user']['userId'],
            'description' => "%{$searchTerm}%",
    ];

    $articles = $this->db->query(
            "SELECT *
      FROM articles 
      WHERE user_id = :user_id
      AND description LIKE :description
      LIMIT {$length} OFFSET {$offset}",
            $params
    )->findAll(Article::class);
    $articleCount = $this->db->query(
            "SELECT COUNT(*)
      FROM articles 
      WHERE user_id = :user_id
      AND description LIKE :description",
            $params
    )->count();

    return [$articles, $articleCount];
  }

  public function getArticleById(string $id)
  {
    return $article = $this->db->query(
            "SELECT * FROM articles WHERE id = :id",
            [
                    'id' => $id,
            ]
    )->find(Article::class);
  }

  public function update(array $formData, int $id)
  {
    $this->db->query(
            "UPDATE articles
      SET description = :description,
        title = :title,
        article_text = :article_text
      WHERE id = :id",
            [
                    'title'        => $formData['title'],
                    'description'  => $formData['description'],
                    'article_text' => $formData['text'],
                    'id'           => $id,
            ]
    );
  }

  public function delete(int $id)
  {
    $this->db->query(
            "DELETE FROM articles WHERE id = :id",
            [
                    'id' => $id,
            ]
    );
  }
}
