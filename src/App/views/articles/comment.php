<div class="comment">
    <div class="comment-avatar">
        <img src="../assets/img/user.webp" alt="profile picture">
    </div>
    <div class="comment-info">
        <h4><?php
          echo escapeInjection($comment['username']); ?></h4>
        <p><?php
          echo escapeInjection($comment['comment_text']); ?></p>
        <p class="comment-info__date"><?php
          echo escapeInjection($comment['created_at']); ?></p>
    </div>
  <?php

  if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $comment['user_id']) : ?>
      <div class="comment-actions">
          <img class="comment-actions__icon comment-actions__edit" src="../assets/img/icons8-edit.svg" alt="">
          <img class="comment-actions__icon comment-actions__delete" src="../assets/img/icons8-trash.svg" alt="">
      </div>
  <?php
  endif; ?>
</div>