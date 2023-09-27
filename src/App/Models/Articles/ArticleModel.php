<?php

declare(strict_types=1);

namespace App\Models\Articles;

use Framework\Exceptions\ValidationException;
use App\Config\Paths;
use Framework\Model;

class ArticleModel extends Model
{
  public int $id;
  public string $title;
  public string $description;
  public string $articleText;
  public string $createdAt;
  public string $updatedAt;
  public int $userId;
  public string $originalFilename;
  public string $storageFilename;
  public string $mediaType;

  public function create(array $formData, $coverImage = null)
  {
    $fileExtension = pathinfo($coverImage['name'], PATHINFO_EXTENSION);
    $newFilename = bin2hex(random_bytes(16)).".".$fileExtension;
    $uploadPath = Paths::STORAGE_UPLOADS."/".$newFilename;
    if (!move_uploaded_file($coverImage['tmp_name'], $uploadPath)) {
      throw new ValidationException(['cover' => ['Failed to upload file']]);
    }
    $this->db->query(
            "INSERT INTO articles(user_id, title, description, article_text,original_filename, storage_filename, media_type) 
            VALUES(:user_id, :title, :description, :article_text, :original_filename, :storage_filename, :media_type)",
            [
                    'user_id'           => $_SESSION['user']['id'],
                    'title'             => $formData['title'],
                    'description'       => $formData['description'],
                    'article_text'      => $formData['article_text'],
                    'original_filename' => $coverImage['name'],
                    'storage_filename'  => $newFilename,
                    'media_type'        => $coverImage['type']
            ]
    );
  }

  public function getAllArticles(int $length = 3, int $offset = 0): array
  {
    $searchTerm = addcslashes($_GET['s'] ?? '', '%_');
    $params = [

            'description' => "%{$searchTerm}%",
    ];

    $articles = $this->db->query(
            "SELECT *
      FROM articles 
      WHERE description LIKE :description
      LIMIT {$length} OFFSET {$offset}",
            $params
    )->findAll();
    $articles = $this->attachImagesToArticlesArray($articles);
    $articleCount = $this->db->query(
            "SELECT COUNT(*)
      FROM articles 
      WHERE description LIKE :description",
            $params
    )->count();

    return [$articles, $articleCount];
  }

  public function getArticlesOfAuthor(int $length = 3, int $offset = 0): array
  {
    $searchTerm = addcslashes($_GET['s'] ?? '', '%_');
    $params = [
            'user_id'     => $_SESSION['user'],
            'description' => "%{$searchTerm}%",
    ];

    $articles = $this->db->query(
            "SELECT *
      FROM articles 
      WHERE user_id = :user_id
      AND description LIKE :description
      LIMIT {$length} OFFSET {$offset}",
            $params
    )->findAll();

    $articles = $this->attachImagesToArticles($articles);

    $articleCount = $this->db->query(
            "SELECT COUNT(*)
      FROM articles 
      WHERE user_id = :user_id
      AND description LIKE :description",
            $params
    )->count();

    return [$articles, $articleCount];
  }

  public function attachImagesToArticlesArray($articles): array
  {
    return array_map(function($article) {
      return $this->attachImageToArticle($article);
    }, $articles);
  }

  public function attachImageToArticle($article): array {
    $filename = $article['storage_filename'];
    $fileDir = Paths::STORAGE_UPLOADS;
    $file = $fileDir.DIRECTORY_SEPARATOR.$filename;
    if (file_exists($file)) {
      $b64image = base64_encode(file_get_contents($file));
      $article['b64image'] = $b64image;
    }
    return $article;
  }

  public function getArticleById(string $id)
  {
    return $article = $this->db->query(
            "SELECT * FROM articles WHERE id = :id",
            [
                    'id' => $id,
            ]
    )->find();
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
                    'title'        => $formData['description'],
                    'description'  => $formData['description'],
                    'article_text' => $formData['article_text'],
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
