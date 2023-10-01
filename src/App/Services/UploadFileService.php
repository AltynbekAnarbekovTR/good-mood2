<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;
use App\Config\Paths;

class UploadFileService
{
  public function __construct()
  {
  }

  public function checkUploadIsImage(?array $file)
  {
    if (!$file || !empty($file['error'])) {
      throw new ValidationException(
              [
                      'cover' => ['Failed to upload file'],
              ]
      );
    }

    $maxFileSizeMB = 3 * 1024 * 1024;

    if ($file['size'] > $maxFileSizeMB) {
      throw new ValidationException(
              [
                      'cover' => ['File upload is too large'],
              ]
      );
    }

    $originalFileName = $file['name'];

    if (!preg_match('/^[A-za-z0-9\s._-]+$/', $originalFileName)) {
      throw new ValidationException(
              [
                      'cover' => ['Invalid filename'],
              ]
      );
    }

    $clientMimeType = $file['type'];
    $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png'];

    if (!in_array($clientMimeType, $allowedMimeTypes)) {
      throw new ValidationException(
              [
                      'cover' => ['Invalid file type'],
              ]
      );
    }
  }

  public function uploadImageToStorage($img)
  {
    $fileExtension = pathinfo($img['name'], PATHINFO_EXTENSION);
    $newFilename = bin2hex(random_bytes(16)).".".$fileExtension;
    $uploadPath = Paths::STORAGE_UPLOADS."/".$newFilename;
    if (!move_uploaded_file($img['tmp_name'], $uploadPath)) {
      throw new ValidationException(['cover' => ['Failed to upload file']]);
    }

    return $newFilename;
  }
}
