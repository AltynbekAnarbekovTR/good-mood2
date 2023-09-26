<?php

declare(strict_types=1);

namespace Framework;

use App\Config\Paths;

class TemplateEngine
{
    private array $globalTemplateData = [];
    private string $pathToPublic = Paths::SCRIPTS;

    public function __construct(private string $basePath)
    {
    }

    public function render(string $template, array $data = [])
    {
        extract($data, EXTR_SKIP);
        extract($this->globalTemplateData, EXTR_SKIP);
        require $this->resolve('layout/layout.php');
    }

    public function resolve(string $path)
    {
        return "{$this->basePath}/{$path}";
    }

    public function resolvePathToScripts(string $path) {
      return "{$this->pathToPublic}/{$path}";
    }

    public function addGlobal(string $key, mixed $value)
    {
        $this->globalTemplateData[$key] = $value;
    }
}
