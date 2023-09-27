<div class="profile">
    <div class="profile__left">
        <img alt="avatar" class="profile-image rounded-circle m-4" src='<?php
        echo array_key_exists(
                'b64image',
                $user
        ) ? "data:image/png;base64,".$user['b64image'] : '/assets/img/user.webp'; ?>'>
        <form enctype="multipart/form-data" method="POST" class="profile__image__container">
          <?php
          include $this->resolve('partials/_csrf.php'); ?>
            <label>
                <input name="avatar" type="file" class="avatar-input">
            </label>
          <?php
          if (array_key_exists('cover', $errors)) : ?>
              <div class="bg-gray-100 mt-2 p-2 text-red-500">
                <?php
                echo escapeInjection($errors['cover'][0]); ?>
              </div>
          <?php
          endif; ?>
        </form>
    </div>
    <div class="profile__right">
        <div class="profile__info">
            <label class="profile__info__field"><b>Username: </b><span><?php
                echo escapeInjection($user['username']); ?></span>
                <a href="/profile/changeUsername" class="profile__info__change">Change</a>
            </label>
            <label class="profile__info__field"><b>Email: </b><span><?php
                echo escapeInjection($user['email']); ?></span>
                <a href="/profile/changeEmail" class="profile__info__change">Change</a>
            </label>
        </div>
    </div>

</div>