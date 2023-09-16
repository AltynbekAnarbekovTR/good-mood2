<?php

declare(strict_types=1);

namespace App\Models\Users;

use App\Config\Paths;
use Framework\Database;
use Framework\Exceptions\ValidationException;
use Framework\Model;

class UserModel extends Model
{
  public int $id;
  public string $email;
  public string $password;
  public string $role;
  public string $createdAt;
  public int $emailVerified;

  public function isEmailTaken(string $email)
  {
    $emailCount = $this->db->query(
            "SELECT COUNT(*) FROM users WHERE email = :email",
            [
                    'email' => $email,
            ]
    )->count();

    if ($emailCount > 0) {
      throw new ValidationException(['email' => ['Email taken']]);
    }
  }

  public function create(array $formData)
  {
    $password = password_hash($formData['password'], PASSWORD_BCRYPT, ['cost' => 12]);
    $this->db->query(
            "INSERT INTO users(email,password) VALUES(:email, :password )",
            [
                    'email'    => $formData['email'],
                    'password' => $password
            ]
    );
    session_regenerate_id();
    $_SESSION['user'] = $this->db->lastInsertId();
  }

  public function setAuthCode(string $authCode, string $email)
  {
    $lastCreatedUser = $this->db->lastInsertId();
    $this->db->query(
            "INSERT INTO auth_codes(user_id, email,code) VALUES(:user_id, :email, :code )",
            [
                    'user_id' => $lastCreatedUser,
                    'email' => $email,
                    'code'  => $authCode,
            ]
    );
  }

  public function getAuthCode($email)
  {
    $accountVerificationRow = $this->db->query(
            "SELECT * FROM auth_codes WHERE email = :email",
            [
                    'email' => $email,
            ]
    )->find();
    return $accountVerificationRow['code'];
  }

  public function verifyAccount($email)
  {
    $this->db->query(
            "UPDATE users SET email_verified = 1 WHERE email =:email",
            [
                    'email' => $email,
            ]
    )->find();

    $user = $this->db->query(
            "SELECT * FROM users WHERE email = :email",
            [
                    'email' => $email,
            ]
    )->find();

    session_regenerate_id();
    $_SESSION['user'] = $user;
  }

  public function login(array $formData)
  {
    $user = $this->db->query(
            "SELECT * FROM users WHERE email = :email",
            [
                    'email' => $formData['email'],
            ]
    )->find();
    if (!$user['email_verified']) {
      throw new ValidationException(['otherLoginErrors' => ['You need to verify your email']]);
    }

    $passwordsMatch = password_verify(
            $formData['password'],
            $user['password'] ?? ''
    );

    if (!$user || !$passwordsMatch) {
      throw new ValidationException(['password' => ['Invalid credentials']]);
    }

    session_regenerate_id();
    $_SESSION['user'] = $user;

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
    )->findAll();


    $userCount = $this->db->query(
            "SELECT COUNT(*)
      FROM users 
      WHERE description LIKE :description",
            $params
    )->count();

    return [$users, $userCount];
  }
}
