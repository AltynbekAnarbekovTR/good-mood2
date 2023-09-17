<header class="bg-indigo-900">
    <nav class="mx-auto flex container items-center justify-between py-4" aria-label="Global">
        <a href="/" class="-m-1.5 p-1.5 text-white text-2xl font-bold">Positive News</a>
        <!-- Navigation Links -->
        <div class="flex lg:gap-x-10">
            <a href="/" class="text-gray-300 hover:text-white transition">Home</a>
            <a href="/about" class="text-gray-300 hover:text-white transition">About</a>
          <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') : ?>
              <a href="/manageUsers" class="text-gray-300 hover:text-white transition">Users</a>
              <a href="/manageArticles" class="text-gray-300 hover:text-white transition">Articles</a>
          <?php endif; ?>
            <?php
            if (isset($_SESSION['user'])) : ?>
                <a href="/logout" class="text-gray-300 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6 inline-block">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>

                    Logout
                </a>
            <?php
            else : ?>
                <a href="/login" class="text-gray-300 hover:text-white transition">Login</a>
                <a href="/register" class="text-gray-300 hover:text-white transition">Register</a>
            <?php
            endif; ?>
        </div>
    </nav>
</header>