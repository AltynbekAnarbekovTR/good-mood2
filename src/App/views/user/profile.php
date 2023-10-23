<div class="profile">
    <div class="profile__left">
        <div class="profile__avatar-container">
            <img alt="avatar" class="profile__avatar m-4"
                 src='<?php echo $userAvatar ? "data:image/png;base64,".$userAvatar : '/assets/img/user.webp'; ?>'>
        </div>
        <form enctype="multipart/form-data" method="POST" class="profile__image__container">
          <?php include $this->resolve('partials/_csrf.php'); ?>
            <label class="profile__avatar-input">
                <span class="text-gray-700">Avatar Image</span>
                <input name="avatar" type="file" class="avatar-input block text-sm text-slate-500 mt-4 file:mr-4 file:py-2 file:px-8 file:border-0 file:text-sm file:font-semibold file:bg-violet-100 file:text-violet-700 hover:file:bg-violet-200" />
              <?php if (array_key_exists('cover', $errors)) : ?>
                  <div class="bg-gray-100 mt-2 p-2 text-red-500">
                    <?php echo escapeInjection($errors['cover'][0]); ?>
                  </div>
              <?php endif; ?>
            </label>
          <?php if (array_key_exists('cover', $errors)) : ?>
              <div class="bg-gray-100 mt-2 p-2 text-red-500">
                <?php echo escapeInjection($errors['cover'][0]); ?>
              </div>
          <?php endif; ?>
        </form>
    </div>
    <div class="profile__right">
        <div class="profile__info">
            <label class="profile__info__field">
                <b>Username: </b><span><?php echo escapeInjection($user->getUsername()); ?></span>
                <a href="/profile/change-username" class="profile__info__change">Change</a>
            </label>
            <label class="profile__info__field">
                <b>Email: </b><span><?php echo escapeInjection($user->getEmail()); ?></span>
                <a href="/profile/change-email" class="profile__info__change">Change</a>
            </label>
            <a href="/reset-password">Change password</a>
        </div>
    </div>
</div>