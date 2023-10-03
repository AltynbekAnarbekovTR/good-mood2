<?php

namespace App\Views;

use App\Config\Paths;
use Framework\TemplateEngine;

class View
//        extends TemplateEngine
{
//  public function renderView(array $data = [])
//  {
//    $this->render($this->basePath.'articles/manageArticles.php', $data);
//  }
//  public function __construct(private TemplateEngine $templateEngine)
//  {
//  }

//    public function renderManageArticles(array $data = [])
//  {
//    $this->templateEngine->render($this->basePath.'articles/manageArticles.php', $data);
//  }
  public function __construct(protected TemplateEngine $templateEngine)
  {
  }
}