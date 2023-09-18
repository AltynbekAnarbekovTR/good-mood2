<div class="wrapper">
    <main>
        <div class="container hero--contained">
            <div class="">
                <img
                        src='data:image/png;base64,<?php
                        echo $article['b64image']; ?>'
                        class="featured__image"
                        alt="article image"
                />
                <img src="<?php
                echo escapeInjection($articleImage); ?>" alt="">
            </div>

            <div class="article">
                    <h1 class="article__title col--12 text__align--left pb--medium">
                      <?php
                      echo escapeInjection($article['title']); ?>
                    </h1>
                <!-- main content -->
                <div class="article__content col--8--last col--12--bp3">
                    <div class="intro__paragraph pb--medium">
                        <p>
                          <?php
                          echo escapeInjection($article['description']); ?>
                        </p>
                    </div>

                    <div class="text__block margin--flex pb--medium">
                        <p>
                          <?php
                          echo escapeInjection($article['article_text']); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
