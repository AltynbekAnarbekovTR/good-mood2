<?php

declare(strict_types=1);

namespace App\Models;

use Framework\ActiveRecordEntity;

class User extends ActiveRecordEntity
{
  protected int $id;
  protected string $username;
  protected string $email;
  protected string $password;
  protected string $role;
  protected string $createdAt;
  protected int $emailVerified;
  protected string|null $originalFilename;
  protected string|null $storageFilename;
  protected string|null $mediaType;

  public function getId(): int
  {
    return $this->id;
  }
  public function getUsername(): string
  {
    return $this->username;
  }
  public function getEmail(): string
  {
    return $this->email;
  }
  public function getPassword(): string
  {
    return $this->password;
  }
  public function getRole(): string
  {
    return $this->role;
  }
  public function getCreatedAt(): string
  {
    return $this->createdAt;
  }
  public function getEmailVerified(): int
  {
    return $this->emailVerified;
  }
  public function getOriginalFilename(): string|null
  {
    return $this->originalFilename;
  }
  public function getStorageFilename(): string|null
  {
    return $this->storageFilename;
  }
  public function getMediaType(): string|null
  {
    return $this->mediaType;
  }

  public function isEmailTaken(string $email)
  {
    $emailCount = $this->db->query(
            "SELECT COUNT(*) FROM users WHERE email = :email",
            [
                    'email' => $email,
            ]
    )->count();

    if ($emailCount > 0) {
      return true;
    }
    return false;
  }

  public function create(array $formData)
  {
    $password = password_hash($formData['password'], PASSWORD_BCRYPT, ['cost' => 12]);
    $this->db->query(
            "INSERT INTO users(username, email,password) VALUES(:username, :email, :password )",
            [
                    'username' => $formData['username'],
                    'email'    => $formData['email'],
                    'password' => $password
            ]
    );
  }

  public function verifyAccount($email)
  {
    $this->db->query(
            "UPDATE users SET email_verified = 1 WHERE email =:email",
            [
                    'email' => $email,
            ]
    );
    $user = $this->db->query(
            "SELECT * FROM users WHERE email = :email",
            [
                    'email' => $email,
            ]
    )->find(User::class);
    return $user;
  }

  public function login(array $formData)
  {
    $user = $this->db->query(
            "SELECT * FROM users WHERE email = :email",
            [
                    'email' => $formData['email'],
            ]
    )->find(User::class);
    if($user) {
      $passwordsMatch = password_verify(
              $formData['password'],
              $user->getPassword() ?? ''
      );
    }
    return $user;
  }

  public function logout()
  {
    unset($_SESSION['user']);
    session_regenerate_id();
  }

  public function getAllUsers(int $length = 3, int $offset = 0): array
  {
    $searchTerm = addcslashes($_GET['s'] ?? '', '%_');
    $params = [

            'description' => "%{$searchTerm}%",
    ];

    $users = $this->db->query(
            "SELECT *
      FROM users 
      WHERE description LIKE :description
      LIMIT {$length} OFFSET {$offset}",
            $params
    )->findAll(User::class);


    $userCount = $this->db->query(
            "SELECT COUNT(*)
      FROM users 
      WHERE description LIKE :description",
            $params
    )->count();

    return [$users, $userCount];
  }

  public function checkUserExist(string $email)
  {
    $user = $this->db->query(
            "SELECT * FROM users WHERE email = :email",
            [
                    'email' => $email,
            ]
    )->find(User::class);
    if($user) return true;
    return false;
  }

  public function changePassword(string $password, string $email)
  {
    $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    $this->db->query(
            "UPDATE users SET password = :password WHERE email =:email",
            [
                    'password' => $password,
                    'email'    => $email,
            ]
    );
  }

  public function changeAvatar($img, string $newFilename, int $userId)
  {
    $this->db->query(
            "UPDATE users
      SET original_filename = :original_filename,
        storage_filename = :storage_filename,
        media_type = :media_type
      WHERE id = :id",
            [
                    'original_filename' => $img['name'],
                    'storage_filename'  => $newFilename,
                    'media_type'        => $img['type'],
                    'id'                => $userId,
            ]
    );
  }

  public function getUserById(int $id)
  {
    $user = $this->db->query(
            "SELECT * FROM users WHERE id = :id",
            [
                    'id' => $id,
            ]
    )->find(User::class);

    return $user;
  }

  public function changeUsername(string $username, int $userId)
  {
    $this->db->query(
            "UPDATE users
      SET username = :username
      WHERE id = :id",
            [
                    'username' => $username,
                    'id'       => $userId,
            ]
    );
  }

  public function changeEmail(string $email, int $userId)
  {
    $this->db->query(
            "UPDATE users
      SET email = :email
      WHERE id = :id",
            [
                    'email' => $email,
                    'id'       => $userId,
            ]
    );
  }
}
