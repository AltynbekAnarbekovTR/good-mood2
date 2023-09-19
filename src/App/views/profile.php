<div class="profile">
    <div class="profile__image">
        <img src="assets/img/user.webp" alt="profile picture">
    </div>
    <div class="profile__info">
        <label class="profile__info__field"><b>Username: </b><span><?php
            echo escapeInjection($user['username']); ?></span>
        </label>
        <label class="profile__info__field"><b>Email: </b><span><?php
            echo escapeInjection($user['email']); ?></span>
        </label>
    </div>
</div>