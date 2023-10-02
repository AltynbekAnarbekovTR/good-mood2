<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\Paths;

class UploadFileService
{
  public function __construct()
  {
  }

  public function checkUploadedImage(array $file)
  {
    if (!$file || !empty($file['error'])) {
      return ['cover' => ['Failed to upload file']];
    }
    if (!strlen($file['name'])) {
      return ['cover' => ['Image is required']];
    }
    $maxFileSizeMB = 3 * 1024 * 1024;
    if ($file['size'] > $maxFileSizeMB) {
      return ['cover' => ['File upload is too large']];
    }
    $originalFileName = $file['name'];
    if (!preg_match('/^[A-za-z0-9\s._-]+$/', $originalFileName)) {
      return ['cover' => ['Invalid filename']];
    }
    $clientMimeType = $file['type'];
    $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!in_array($clientMimeType, $allowedMimeTypes)) {
      return ['cover' => ['Invalid file type']];
    }
  }

  public function uploadImageToStorage($img)
  {
    $fileExtension = pathinfo($img['name'], PATHINFO_EXTENSION);
    $newFilename = bin2hex(random_bytes(16)).".".$fileExtension;
    $uploadPath = Paths::STORAGE_UPLOADS."/".$newFilename;
    $fileIsUploaded = move_uploaded_file($img['tmp_name'], $uploadPath);
    return [$newFilename, $fileIsUploaded];
  }
}
