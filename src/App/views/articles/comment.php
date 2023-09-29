<div id="<?php echo $comment['id']?>" class="comment">
  <?php include $this->resolve("partials/_csrf.php"); ?>
    <div class="comment__left">
        <div class="comment-avatar">
            <img class="comment-avatar" src="data:image/png;base64,<?php
            echo $comment['b64image']; ?>" alt="profile picture">
        </div>
    </div>
    <div class="comment__right">
        <div class="comment-info">
            <h4 class="comment-info__username"><?php
              echo escapeInjection($comment['username']); ?></h4>
            <textarea maxlength="500" readonly class="comment-info__text"><?php
              echo escapeInjection($comment['comment_text']); ?></textarea>
            <p class="comment-info__date"><?php
              echo escapeInjection($comment['created_at']); ?></p>
        </div>
    </div>

  <?php
  if (isset($_SESSION['user']) && $_SESSION['user']['userId'] === $comment['user_id']) : ?>
      <div class="comment-actions">
          <button class="comment-actions__icon comment-actions__edit">
              <img src="../assets/img/icons8-edit.svg" alt="">
          </button>
          <a href="/delete-comment/<?php
          echo $comment['id'] ?>" class="comment-actions__icon">
              <img src="../assets/img/icons8-trash.svg" alt="">
          </a>
      </div>
  <?php
  endif; ?>
</div>