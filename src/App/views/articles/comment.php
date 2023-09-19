<div class="comment">
    <div class="comment-avatar">
        <img src="assets/img/user.webp" alt="profile picture">
    </div>
    <div class="comment-info">
        <h4><?php
          echo escapeInjection($comment['username']); ?></h4>
        <p><?php
          echo escapeInjection($comment['comment_text']); ?></p>
        <p class="comment-info__date"><?php
          echo escapeInjection($comment['created_at']); ?></p>
    </div>

</div>