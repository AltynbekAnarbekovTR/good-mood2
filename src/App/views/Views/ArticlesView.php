<?php

namespace App\Views;

class ArticlesView extends View {
  public function getManageArticlesTemplate(array $data = []): string
  {
    return $this->templateEngine->render('articles/manageArticles.php', $data);
  }

  public function getCreateArticleTemplate(array $data = []): string
  {
    return $this->templateEngine->render('articles/create.php', $data);
  }

  public function getEditArticleTemplate(array $data = []): string
  {
    return $this->templateEngine->render('articles/edit.php', $data);
  }

  public function getReadArticleTemplate(array $data = []): string
  {
    return $this->templateEngine->render('articles/article.php', $data);
  }
}