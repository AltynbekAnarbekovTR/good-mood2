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
          <a href="/editComment/<?php
          echo $comment['id'] ?>" class="comment-actions__icon comment-actions__edit">
              <img src="../assets/img/icons8-edit.svg" alt="">
          </a>
          <a href="/deleteComment/<?php
          echo $comment['id'] ?>" class="comment-actions__icon comment-actions__edit">
              <img src="../assets/img/icons8-trash.svg" alt="">
          </a>
      </div>
  <?php
  endif; ?>
</div>