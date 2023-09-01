<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class PasswordRule implements RuleInterface
{
  public function validate(array $data, string $field, array $params): bool
  {
    if (strpos($data[$field], ' ') !== false || strlen($data[$field]) < 8 || !preg_match('/[A-Z]/', $data[$field]) || !preg_match('/[a-z]/', $data[$field]) || !preg_match('/[0-9]/', $data[$field])) {
        return (bool) false;
    }
    return (bool) true;
  }

  public function getMessage(array $data, string $field, array $params): string
  {
    return "Password must not contain blank spaces and be at least 8 characters long, contain at least 1 uppercase, 1 lowercase letter and 1 digit";
  }
}
