<div class="wrapper">
    <main>
        <div class="container hero--contained">
            <div class="">
                <img
                        src='data:image/png;base64,<?php
                        echo $articleImage; ?>'
                        class="featured__image"
                        alt="article image"
                />
            </div>

            <div class="article">
                <h1 class="article__title col--12 text__align--left pb--medium">
                  <?php
                  echo escapeInjection($article->getTitle()); ?>
                </h1>
                <!-- main content -->
                <div class="article__content col--8--last col--12--bp3">
                    <div class="intro__paragraph pb--medium">
                        <p>
                          <?php
                          echo escapeInjection($article->getDescription()); ?>
                        </p>
                    </div>

                    <div class="text__block margin--flex pb--medium">
                        <p>
                          <?php
                          echo escapeInjection($article->getArticleText()); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="comments pb--medium">
                <form enctype="multipart/form-data" method="POST" class="grid grid-cols-1 gap-6 comment-form">
                  <?php
                  include $this->resolve("partials/_csrf.php"); ?>
                    <span class="lh-0 light-grey">(Max: 500 symbols)</span>
                    <textarea maxlength="500" required name="comment_text" type="text" placeholder="Type your comment..."
                              class="comment-form__text"></textarea>
                    <div class="comment-form__buttons">
                        <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded comment-form__button">
                            Submit
                        </button>
                    </div>

                </form>
                <div class="comments-list">
                  <?php
                  foreach ($comments as $comment) : ?>
                    <?php
                    include $this->resolve("articles/comment.php"); ?>
                  <?php
                  endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</div>
