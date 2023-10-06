<?php

namespace App\Views;

use App\Models\Article;

class HeroArticleView extends View {
  public function renderHeroArticle(array $data = [])
  {
    return $this->templateEngine->render('articles/heroArticle.php', $data);
  }
}