<?php

function dump_and_die($value)
{
    echo '<br>';
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    die();
}

function e(mixed $value): string
{
  return htmlspecialchars((string) $value);
}
