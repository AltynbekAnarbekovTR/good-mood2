<?php

declare(strict_types=1);

namespace App\Models;

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
  protected string|null $categories;
  protected string|null $mainFor;

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

  public function getCategories(): array|null
  {
    if($this->categories !== null) {
      return unserialize($this->categories);
    } else return [];
  }

  public function getMainFor(): string|null
  {
    return $this->mainFor;
  }

  public function setMainFor($articleId, $pageName) {
    $this->db->query(
            "UPDATE articles
      SET main_for = :main_for
      WHERE main_for = :pageName",
            [
                    'main_for'        => null,
                    'pageName'           => $pageName
            ]
    );
    $this->db->query(
            "UPDATE articles
      SET main_for = :main_for
      WHERE id = :id",
            [
                    'main_for'        => $pageName,
                    'id'           => $articleId
            ]
    );
  }

  public function getMainArticle($pageName) {
    return $this->db->query(
            "SELECT * FROM articles
      WHERE main_for = :main_for",
            [
                    'main_for'        => $pageName
            ]
    )->find(Article::class);
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
                    'media_type'        => $coverImage['type']
            ]
    );
  }

  public function setCategories(array $categories)
  {
    $articleId = $this->lastInsertId();
    foreach ($categories as $category) {
      $categoryId = $this->db->query(
              "SELECT id FROM categories WHERE title=:category",
              [
                      'category' => $category,
              ]
      )->find();
      $this->db->query(
              "INSERT INTO article_categories(article_id, categorie_id) 
            VALUES(:article_id, :categorie_id)",
              [
                      'article_id'   => $articleId,
                      'categorie_id' => $categoryId['id'],
              ]
      );
    }
  }

  public function getAllArticles(int $length = 3, int $offset = 0, $category = ''): array
  {
    $searchTerm = addcslashes($_GET['s'] ?? '', '%_');
    if ($category) {
      $params = [
              'category' => $category,
              'title'    => "%{$searchTerm}%",
      ];
      $articles = $this->db->query(
              "SELECT a.*
            FROM articles a
            JOIN article_category ac ON a.id = ac.article_id
            JOIN categories c ON ac.category_id = c.id
            WHERE a.title LIKE :title
            AND c.title = :category
            LIMIT {$length} OFFSET {$offset}",
              array_merge($params, ['category' => $category])
      )->findAll(Article::class);
      $articleCount = $this->db->query(
              "SELECT COUNT(*)
            FROM articles a
            JOIN article_category ac ON a.id = ac.article_id
            JOIN categories c ON ac.category_id = c.id
            WHERE a.title LIKE :title
            AND c.title = :category",
              $params
      )->count();
    }
    else {
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
    }

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
    $serializedCategories = isset($formData['category']) ? serialize($formData['category']) : null;
    $this->db->query(
            "UPDATE articles
      SET description = :description,
        title = :title,
        article_text = :article_text,
        categories = :categories
      WHERE id = :id",
            [
                    'title'        => $formData['title'],
                    'description'  => $formData['description'],
                    'article_text' => $formData['text'],
                    'id'           => $id,
                    'categories'   => $serializedCategories,
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

  public function addCategoriesToArticle(int $articleId, array $categoryTitles)
  {
    $existingCategories = $this->db->query(
            "SELECT category_id FROM article_category WHERE article_id = :article_id",
            [
                    'article_id' => $articleId,
            ]
    )->findAll();
    $categoryIdsToAdd = [];
    foreach ($categoryTitles as $categoryTitle) {
      $categoryId = $this->db->query(
              "SELECT id FROM categories WHERE title = :categoryTitle",
              ['categoryTitle' => $categoryTitle]
      )->find();
      if ($categoryId) {
        $categoryIdsToAdd[] = $categoryId['id'];
      }
    }
    $newCategories = array_diff($categoryIdsToAdd, $existingCategories);
    foreach ($newCategories as $categoryId) {
      $this->db->query(
              "INSERT INTO article_category (article_id, category_id) VALUES (:article_id, :category_id)",
              ['article_id' => $articleId, 'category_id' => $categoryId]
      );
    }
  }

  public function getArticleCategories(int $articleId) {
    $this->db->query(
            "SELECT id FROM article_cateogory WHERE article_id = :article_id JOIN ",
            ['article_id' => $articleId, 'category_id' => $categoryId]
    );
  }
}
