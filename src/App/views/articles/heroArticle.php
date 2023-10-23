<div class="main__article">
  <a class="main__article__img" href="/article/<?php echo $mainArticle->getId(); ?>">
      <img src="data:image/png;base64,<?php echo $mainArticleImage ?>" alt="Main article">
  </a>
  <div class="card__content">
    <a href="https://www.positive.news/society/electric-van-summer-holidays-with-children/"
       class="card__title h2">
      <?php echo $mainArticle->getTitle(); ?>
    </a>
    <p>
      <?php echo $mainArticle->getDescription(); ?>
    </p>
    <?php foreach ($mainArticleCategories as $articleCategory) : ?>
        <a href="/category/<?php echo $articleCategory ?>" class="card__category"><?php echo $articleCategory ?></a>
    <?php endforeach; ?>
  </div>
</div>