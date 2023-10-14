<div class="main__article">
  <a href="/article/<?php echo $mainArticle->getId(); ?>"
     class="">
      <img class="main__article--img" src="data:image/png;base64,<?php echo $mainArticleImage ?>" alt="Main article">
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
        <span class="card__category"><?php echo $articleCategory ?></span>
    <?php endforeach; ?>
  </div>
</div>