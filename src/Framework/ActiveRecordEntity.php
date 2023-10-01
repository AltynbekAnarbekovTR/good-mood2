<?php

namespace Framework;

abstract class ActiveRecordEntity
{
  public function __construct(protected Database $db)
  {
  }

  public function __set(string $name, $value)
  {
    $camelCaseName = $this->underscoreToCamelCase($name);
    $this->$camelCaseName = $value;
  }

  private function underscoreToCamelCase(string $source): string
  {
    return lcfirst(str_replace('_', '', ucwords($source, '_')));
  }
}