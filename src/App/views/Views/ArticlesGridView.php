<?php

namespace App\Views;

class ArticlesGridView extends View {
  public function renderArticlesGrid(array $data = [])
  {
    return $this->templateEngine->render('articles/articlesGrid.php', $data);
  }
}