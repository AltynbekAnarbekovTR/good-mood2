<header class="bg-indigo-900">
    <nav class="mx-auto flex container items-center justify-between py-4" aria-label="Global">
        <a href="/" class="-m-1.5 p-1.5 text-white text-2xl font-bold">Good Mood</a>
        <!-- Navigation Links -->
        <div class="flex lg:gap-x-10">
            <a href="/" class="text-gray-300 hover:text-white transition">Home</a>
            <a href="/about" class="text-gray-300 hover:text-white transition">About</a>
                <div class="header__nav__dropdown">
                    <a class="dropdown__toggle text-gray-300 hover:text-white transition">Sections</a>
                    <ul class="dropdown__content">
                        <li class="dropdown__nav__item  menu-item menu-item-type-taxonomy menu-item-object-category menu-item-32864">
                            <a href="/category/all-articles" class="dropdown__nav__link">All articles</a>
                        </li>
                        <li class="dropdown__nav__item  menu-item menu-item-type-taxonomy menu-item-object-category menu-item-32864">
                            <a href="/category/Society" class="dropdown__nav__link">Society</a>
                        </li>
                        <li class="dropdown__nav__item  menu-item menu-item-type-taxonomy menu-item-object-category menu-item-32865">
                            <a href="/category/Environment" class="dropdown__nav__link">Environment</a>
                        </li>
                        <li class="dropdown__nav__item  menu-item menu-item-type-taxonomy menu-item-object-category menu-item-32867">
                            <a href="/category/Lifestyle" class="dropdown__nav__link">Lifestyle</a>
                        </li>
                        <li class="dropdown__nav__item  menu-item menu-item-type-taxonomy menu-item-object-category menu-item-32870">
                            <a href="/category/Science" class="dropdown__nav__link">Science</a>
                        </li>
                        <li class="dropdown__nav__item  menu-item menu-item-type-taxonomy menu-item-object-category menu-item-32866">
                            <a href="/category/Economics" class="dropdown__nav__link">Economics</a>
                        </li>
                        <li class="dropdown__nav__item  menu-item menu-item-type-taxonomy menu-item-object-category menu-item-32869">
                            <a href="/category/Opinion" class="dropdown__nav__link">Opinion</a>
                        </li>
                        <li class="dropdown__nav__item  menu-item menu-item-type-taxonomy menu-item-object-category menu-item-32868">
                            <a href="/category/World" class="dropdown__nav__link">World</a>
                        </li>
                    </ul>
                </div>
          <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') : ?>
              <a href="/manage-articles" class="text-gray-300 hover:text-white transition">Articles</a>
          <?php endif; ?>
            <?php if (isset($_SESSION['user']['loggedIn']) && $_SESSION['user']['loggedIn'] === true) : ?>
                <a href="/profile" class="text-gray-300 hover:text-white transition">Profile</a>
                <a href="/logout" class="text-gray-300 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6 inline-block">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                    Logout
                </a>
            <?php else : ?>
                <a href="/login" class="text-gray-300 hover:text-white transition">Login</a>
                <a href="/register" class="text-gray-300 hover:text-white transition">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>