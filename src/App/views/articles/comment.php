<div id="<?php echo $comment->getId();?>" class="comment">
  <?php include $this->resolve("partials/_csrf.php"); ?>
    <div class="comment__left">
        <div class="comment-avatar">
            <img class="comment-avatar"
                 src='<?php echo $userAvatars[$comment->getUserId()] ? "data:image/png;base64,".$userAvatars[$comment->getUserId()] : '/assets/img/user.webp'; ?>'>
        </div>
    </div>
    <div class="comment__right">
        <div class="comment-info">
            <h4 class="comment-info__username">
              <?php echo escapeInjection($comment->getUsername()); ?>
            </h4>
            <textarea maxlength="500" readonly class="comment-info__text">
              <?php echo escapeInjection($comment->getCommentText()); ?>
            </textarea>
            <p class="comment-info__date">
              <?php echo escapeInjection($comment->getCreatedAt()); ?>
            </p>
        </div>
    </div>
  <?php if (isset($_SESSION['user']) && $_SESSION['user']['userId'] === $comment->getUserId()) : ?>
      <div class="comment-actions">
          <button class="comment-actions__icon comment-actions__edit">
              <img src="../assets/img/icons8-edit.svg" alt="edit">
          </button>
          <a href="/delete-comment/<?php echo $comment->getId() ?>" class="comment-actions__icon">
              <img src="../assets/img/icons8-trash.svg" alt="delete">
          </a>
      </div>
  <?php endif; ?>
</div>