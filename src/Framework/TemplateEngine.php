<?php

declare(strict_types=1);

namespace Framework;

use App\Config\Paths;

class TemplateEngine
{
    protected array $globalTemplateData = [];

    public function __construct(protected string $basePath)
    {
    }
//  public function __construct()
//    {
//    }

    public function render(string $template, array $data = [])
    {
        extract($data, EXTR_SKIP);
        extract($this->globalTemplateData, EXTR_SKIP);
        require $this->resolve('layout/layout.php');
    }

    public function resolve(string $path)
    {
        return "{$this->basePath}{$path}";
    }

    public function setBasePath(string $path) {
      $this->basePath = $path;
    }

//    public function resolvePathToScripts(string $path) {
//      return "{$this->pathToPublic}/{$path}";
//    }

    public function addGlobal(string $key, mixed $value)
    {
        $this->globalTemplateData[$key] = $value;
    }
}
