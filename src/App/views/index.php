<main>
    <div class="wrapper">
        <div class="container">
            <div class="main__article">
                <a href="https://www.positive.news/society/electric-van-summer-holidays-with-children/"
                   class="col--7 col--12--bp3">
                    <img
                            src="https://www.positive.news/wp-content/uploads/2023/07/1-4-1320x880-c-center.jpg"
                            class="main__article--img"
                            alt="Image for How an electric van changed our summer holidays forever"
                    />
                </a>

                <div class="card__content col--5--last col--12--bp3">
                    <a href="https://www.positive.news/society/electric-van-summer-holidays-with-children/"
                       class="card__title h2">How an electric van changed our summer holidays forever</a>
                    <p>
                        An electric Nissan has put a spark in summer holidays for one
                        cash-strapped family, who share their tips for travelling by van
                    </p>
                </div>
            </div>

            <!-- Search Articles -->
          <?php
          include $this->resolve("partials/_searchBar.php"); ?>

            <!-- Articles list -->
            <div class="latest__articles cols--3--2--2">
              <?php foreach ($articles as $article) : ?>
                  <div class="column card">
                      <a href='/article/<?php echo $article->getId(); ?>' class="card__image__link">
                        <?php
                        $image = $articleImages[$article->getId()] ?? '';
                        echo "<img  src='data:image/png;base64,$image' 
                        class='card__image' alt='Image for How to live longer: eight habits that could add years to your life'/>"
                        ?>
                      </a>
                      <div class="card__content">
                          <a href='/article/<?php echo $article->getId(); ?>'
                            class="card__title h3">
                            <?php echo escapeInjection($article->getTitle()); ?>
                          </a>
                          <span class="card__text">
                            <?php echo escapeInjection($article->getDescription());?>
                          </span>
                      </div>
                  </div>
              <?php
              endforeach; ?>
            </div>
        </div>
    </div>
</main>
<!-- End Main Content Area -->