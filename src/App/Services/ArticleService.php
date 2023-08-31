<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\Paths;
use Framework\Database;
use Framework\Exceptions\ValidationException;

class ArticleService
{
    public function __construct(private Database $db)
    {
    }

    public function create(array $formData)
    {
        $receiptFile = $_FILES['receipt'] ?? null;
//      $this->receiptService->validateFile($receiptFile);

        $fileExtension = pathinfo($receiptFile['name'], PATHINFO_EXTENSION);
        $newFilename = bin2hex(random_bytes(16)) . "." . $fileExtension;

        $uploadPath = Paths::STORAGE_UPLOADS . "/" . $newFilename;

//        if (!move_uploaded_file($receiptFile['tmp_name'], $uploadPath)) {
//            throw new ValidationException(['receipt' => ['Failed to upload file']]);
//        }

        $this->db->query(
            "INSERT INTO articles(user_id, title, description, article_text,original_filename, storage_filename, media_type)
      VALUES(:user_id, :title, :description, :article_text, :original_filename, :storage_filename, :media_type)",
            [
                'user_id' => $_SESSION['user'],
                'title' => $formData['title'],
                'description' => $formData['description'],
                'article_text' => $formData['article_text'],
                'original_filename' => $receiptFile['name'],
                'storage_filename' => $newFilename,
                'media_type' => $receiptFile['type']
            ]
        );
    }

    public function getUserArticles(int $length, int $offset)
    {
        $searchTerm = addcslashes($_GET['s'] ?? '', '%_');
        $params = [
            'user_id' => $_SESSION['user'],
            'description' => "%{$searchTerm}%"
        ];

        $articles = $this->db->query(
            "SELECT *
      FROM articles 
      WHERE user_id = :user_id
      AND description LIKE :description
      LIMIT {$length} OFFSET {$offset}",
            $params
        )->findAll();

        $articles = array_map(
            function (array $article) {
                $article['receipts'] = $this->db->query(
                    "SELECT * FROM receipts WHERE article_id = :article_id",
                    ['article_id' => $article['id']]
                )->findAll();

                $filename = $article['storage_filename'];
                $fileDir = Paths::STORAGE_UPLOADS;
                $file = $fileDir . $filename;
                if (file_exists($file)) {
                    $b64image = base64_encode(file_get_contents($file));
                    $article['b64image'] = $b64image;
                }

                return $article;
            },
            $articles
        );

        $articleCount = $this->db->query(
            "SELECT COUNT(*)
      FROM articles 
      WHERE user_id = :user_id
      AND description LIKE :description",
            $params
        )->count();

        return [$articles, $articleCount];
    }

    public function getUserArticle(string $id)
    {
        return $this->db->query(
            "SELECT *
      FROM articles 
      WHERE id = :id AND user_id = :user_id",
            [
                'id' => $id,
                'user_id' => $_SESSION['user']
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
      WHERE id = :id
      AND user_id = :user_id",
            [
                'title' => $formData['description'],
                'description' => $formData['description'],
                'article_text' => $formData['article_text'],
                'id' => $id,
                'user_id' => $_SESSION['user']
            ]
        );
    }

    public function delete(int $id)
    {
        $this->db->query(
            "DELETE FROM articles WHERE id = :id AND user_id = :user_id",
            [
                'id' => $id,
                'user_id' => $_SESSION['user']
            ]
        );
    }
}
