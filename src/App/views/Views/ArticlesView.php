<?php

namespace App\Views;

class ArticlesView extends View {
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

  public function renderManageArticles(array $data = [])
  {
    $this->templateEngine->render('articles/manageArticles.php', $data);
  }

  public function renderCreateArticle(array $data = [])
  {
    $this->templateEngine->render('articles/create.php', $data);
  }

  public function renderEditArticle(array $data = [])
  {
    $this->templateEngine->render('articles/edit.php', $data);
  }

  public function renderReadArticle(array $data = [])
  {
    $this->templateEngine->render('articles/article.php', $data);
  }
}