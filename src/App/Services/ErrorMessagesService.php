<?php

declare(strict_types=1);

namespace App\Services;

class ErrorMessagesService
{
  public function __construct()
  {
  }

  public function setErrorMessage(array $errorMessages)
  {
    $oldFormData = $_POST;
    $excludedFields = ['password', 'confirmPassword'];
    $formattedFormData = array_diff_key(
            $oldFormData,
            array_flip($excludedFields)
    );

    $_SESSION['errors'] = $errorMessages;
    $_SESSION['oldFormData'] = $formattedFormData;

    $referer = $_SERVER['HTTP_REFERER'];
    redirectTo($referer);
  }
}
