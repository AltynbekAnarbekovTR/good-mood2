<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\Paths;

class ImageService
{
  public function __construct()
  {
  }

  public function createB64ImageArray($objects): array
  {
    $b64ImageArray = [];
    foreach ($objects as $object)
    {
      $b64Image = $this->createB64Image($object);
      $b64ImageArray[(string)$object->getId()] = $b64Image;
    }
    return $b64ImageArray;
  }

  public function createB64Image($object): string {
    $filename = $object->getStorageFilename();
    $fileDir = Paths::STORAGE_UPLOADS;
    $file = $fileDir.DIRECTORY_SEPARATOR.$filename;
    if (file_exists($file)) {
      $b64image = base64_encode(file_get_contents($file));
    }
    return $b64image;
  }
}
