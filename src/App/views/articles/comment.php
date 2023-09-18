<div class="comment">
  <p>Username: <?php echo escapeInjection($comment['username']); ?></p>
  <p>Comment text: <?php echo escapeInjection($comment['comment_text']); ?></p>
    <p>Created at: <?php echo escapeInjection($comment['created_at']); ?></p>
</div>