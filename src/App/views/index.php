<main>
    <div class="wrapper">
        <div class="container">
            <div class="main__article">
                <a
                        href="https://www.positive.news/society/electric-van-summer-holidays-with-children/"
                        class="card__image__link col--7 col--12--bp3"
                >
                    <img
                            src="https://www.positive.news/wp-content/uploads/2023/07/1-4-1320x880-c-center.jpg"
                            class="card__image main__article--img img100"
                            alt="Image for How an electric van changed our summer holidays forever"
                    />
                </a>

                <div class="card__content col--5--last col--12--bp3">
                    <a
                            href="https://www.positive.news/society/electric-van-summer-holidays-with-children/"
                            class="card__title h2"
                    >How an electric van changed our summer holidays forever</a
                    >
                    <p>
                        An electric Nissan has put a spark in summer holidays for one
                        cash-strapped family, who share their tips for travelling by van
                    </p>
                    <a
                            href="https://www.positive.news/category/environment/"
                            class="card__category"
                    >Environment</a
                    >
                    <a
                            href="https://www.positive.news/category/lifestyle/"
                            class="card__category"
                    >Lifestyle</a
                    >
                    <a
                            href="https://www.positive.news/category/society/"
                            class="card__category"
                    >Society</a
                    >
                    <a
                            href="https://www.positive.news/category/lifestyle/travel/"
                            class="card__category"
                    >Travel</a
                    >
                </div>
            </div>
            <form method="GET" class="mt-4 w-full">
                <div class="flex">
                    <input value="<?php
                    echo escape((string)$searchTerm); ?>" name="s" type="text"
                           class="w-full rounded-l-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                           placeholder="Enter search term"/>
                    <button type="submit"
                            class="rounded-r-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Search
                    </button>
                </div>
            </form>
            <div class="latest__articles cols--3--2--2">
              <?php
              foreach ($articles as $article) : ?>
                  <div class="column card">
                      <a href='/article/<?php
                      echo $article['id'] ?>'
                         class="card__image__link">
                        <?php
                        $image = $article['b64image'] ?? '';
                        echo "<img  src='data:image/png;base64,$image'
              class='card__image main__article--img img100'
              alt='Image for How to live longer: eight habits that could add years to your life'
            />" ?>
                      </a>

                      <div class="card__content">
                          <a <a href='/article/<?php
                        echo $article['id'] ?>'
                                class="card__title h3">
                          <?php echo escape($article['title']); ?></a>
                          <span class="card__text">
              <?php
              echo escape($article['description']); ?>
            </span>
                          <span class="card__text">
              <?php
              echo escape($article['article_text']); ?>
            </span>
                          <a
                                  href="https://www.positive.news/category/lifestyle/body-mind/"
                                  class="card__category"
                          >Body &amp; Mind</a
                          >
                          <a
                                  href="https://www.positive.news/category/lifestyle/health/"
                                  class="card__category"
                          >Health</a
                          >
                          <a
                                  href="https://www.positive.news/category/lifestyle/"
                                  class="card__category"
                          >Lifestyle</a
                          >
                          <a
                                  href="https://www.positive.news/category/lifestyle/wellbeing/"
                                  class="card__category"
                          >Wellbeing</a
                          >
                      </div>
                  </div>
              <?php
              endforeach; ?>
            </div>
        </div>
    </div>
</main>
<!-- End Main Content Area -->
