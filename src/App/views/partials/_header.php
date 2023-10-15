<nav class="navbar navbar-expand-lg bg-indigo-900">
    <div class="container-fluid">
        <a href="/" class="navbar-brand p-1.5 text-white text-2xl font-bold">Good Mood</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mb-2 mb-lg-0 w-100 justify-content-end">
                <li class="nav-item"><a href="/" class="text-gray-300 hover:text-white transition nav-link">Home</a></li>
                <li class="nav-item"><a href="/about" class="text-gray-300 hover:text-white transition nav-link ">About</a></li>
                <li class="nav-item dropdown">
                    <a class="text-gray-300 hover:text-white focus:text-white nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Sections
                    </a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item">
                            <a href="/category/all-articles" class="dropdown__nav__link">All articles</a>
                        </li>
                        <li class="dropdown-item">
                            <a href="/category/Society" class="dropdown__nav__link">Society</a>
                        </li>
                        <li class="dropdown-item">
                            <a href="/category/Environment" class="dropdown__nav__link">Environment</a>
                        </li>
                        <li class="dropdown-item">
                            <a href="/category/Lifestyle" class="dropdown__nav__link">Lifestyle</a>
                        </li>
                        <li class="dropdown-item">
                            <a href="/category/Science" class="dropdown__nav__link">Science</a>
                        </li>
                        <li class="dropdown-item">
                            <a href="/category/Economics" class="dropdown__nav__link">Economics</a>
                        </li>
                        <li class="dropdown-item">
                            <a href="/category/Opinion" class="dropdown__nav__link">Opinion</a>
                        </li>
                        <li class="dropdown-item">
                            <a href="/category/World" class="dropdown__nav__link">World</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                  <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') : ?>
                      <a aria-current="page" href="/manage-articles" class="text-gray-300 hover:text-white transition nav-link">Articles</a>
                      <a aria-current="page" href="/manage-users" class="text-gray-300 hover:text-white transition nav-link">Users</a>
                  <?php endif; ?>
                </li>
              <?php if (isset($_SESSION['user']['loggedIn']) && $_SESSION['user']['loggedIn'] === true) : ?>
                  <li class="nav-item"><a href="/profile" class="text-gray-300 hover:text-white transition nav-link">Profile</a></li>
                  <li class="nav-item">
                      <a href="/logout" class="text-gray-300 hover:text-white transition nav-link">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                               stroke="currentColor" class="w-6 h-6 inline-block">
                              <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                          </svg>
                          Logout
                      </a>
                  </li>
              <?php else : ?>
                  <li class="nav-item"><a href="/login" class="text-gray-300 hover:text-white transition nav-link">Login</a></li>
                  <li class="nav-item"><a href="/register" class="text-gray-300 hover:text-white transition nav-link">Register</a>
              <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>