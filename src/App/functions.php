<?php

declare(strict_types=1);

function customDump(mixed $value)
{
  echo "<pre>";
  var_dump($value);
  echo "</pre>";
  echo '-------------------';
}

function escapeInjection(mixed $value): string
{
  return htmlspecialchars((string) $value);
}

function redirectTo(string $path)
{
  header("Location: {$path}");
  http_response_code(302);
  exit;
}
