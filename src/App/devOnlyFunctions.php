<?php

declare(strict_types=1);

error_reporting(E_ALL & ~E_NOTICE);

function customDump(mixed $value)
{
echo "<pre>";
  var_dump($value);
  echo "</pre>";
echo '-------------------';
}