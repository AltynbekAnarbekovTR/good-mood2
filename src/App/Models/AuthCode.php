<?php

declare(strict_types=1);

namespace App\Models;

use Framework\ActiveRecordEntity;

class AuthCode extends ActiveRecordEntity
{
  protected int $id;
  protected int $userId;
  protected string $email;
  protected string $code;

  public function getId(): int
  {
    return $this->id;
  }
  public function getUserId(): int
  {
    return $this->userId;
  }
  public function getEmail(): string
  {
    return $this->email;
  }
  public function getCode(): string
  {
    return $this->code;
  }

  public function setAuthCode(string $authCode, string $email)
  {
    $lastCreatedUser = $this->lastInsertId();
    if(!$lastCreatedUser) {
      $lastCreatedUser = $_SESSION['user']['userId'];
    }
    $this->db->query(
            "INSERT INTO auth_codes(user_id, email,code) VALUES(:user_id, :email, :code )",
            [
                    'user_id' => $lastCreatedUser,
                    'email'   => $email,
                    'code'    => $authCode
            ]
    );
  }

  public function getAuthCode($email)
  {
    return $accountVerificationRow = $this->db->query(
            "SELECT * FROM auth_codes WHERE email = :email",
            [
                    'email' => $email,
            ]
    )->find(AuthCode::class);
  }
}
