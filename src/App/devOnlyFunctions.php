<?php

declare(strict_types=1);

function customDump(mixed $value)
{
echo "<pre>";
  var_dump($value);
  echo "</pre>";
echo '-------------------';
}