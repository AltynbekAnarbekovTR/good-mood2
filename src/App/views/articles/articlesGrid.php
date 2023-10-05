<!-- Articles list -->
<div class="latest__articles cols--3--2--2">
  <?php foreach ($articles as $article) : ?>
    <div class="column card">
      <a href='/article/<?php echo $article->getId(); ?>' class="card__image__link">
        <?php $image = $articleImages[$article->getId()] ?? '';
        echo "<img  src='data:image/png;base64,$image' class='card__image' alt='article'/>"?>
      </a>
      <div class="card__content">
        <a href='/article/<?php echo $article->getId(); ?>' class="card__title h3">
          <?php echo escapeInjection($article->getTitle()); ?>
        </a>
        <span class="card__text">
          <?php echo escapeInjection($article->getDescription()); ?>
        </span>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0 mt-6">
    <!-- Previous Page Link -->
    <div class="-mt-px flex w-0 flex-1">
      <?php if ($currentPage > 1) : ?>
          <a href="/manage-articles?<?php echo escapeInjection($previousPageQuery); ?>"
             class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
              <svg class="mr-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M18 10a.75.75 0 01-.75.75H4.66l2.1 1.95a.75.75 0 11-1.02 1.1l-3.5-3.25a.75.75 0 010-1.1l3.5-3.25a.75.75 0 111.02 1.1l-2.1 1.95h12.59A.75.75 0 0118 10z" clip-rule="evenodd" />
              </svg>
              Previous
          </a>
      <?php endif; ?>
    </div>
    <!-- Pages Link -->
    <div class="hidden md:-mt-px md:flex">
      <?php foreach ($pageLinks as $pageNum => $query) : ?>
          <a href="/?<?php echo escapeInjection($query); ?>" class="<?php echo $pageNum + 1 === $currentPage ? "border-indigo-500 text-indigo-600" : "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"; ?>inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium">
            <?php echo $pageNum + 1; ?>
          </a>
      <?php endforeach; ?>
    </div>
    <!-- Next Page Link -->
    <div class="-mt-px flex w-0 flex-1 justify-end">
      <?php if ($currentPage < $lastPage) : ?>
          <a href="/manage-articles?<?php echo escapeInjection($nextPageQuery); ?>" class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
              Next
              <svg class="ml-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M2 10a.75.75 0 01.75-.75h12.59l-2.1-1.95a.75.75 0 111.02-1.1l3.5 3.25a.75.75 0 010 1.1l-3.5 3.25a.75.75 0 11-1.02-1.1l2.1-1.95H2.75A.75.75 0 012 10z" clip-rule="evenodd" />
              </svg>
          </a>
      <?php endif; ?>
    </div>
</nav>