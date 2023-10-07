<form method="GET" class="mt-4 w-full">
  <div class="flex">
    <input name="s" type="text" placeholder="Enter search term"
           value="<?php echo escapeInjection((string)$searchTerm); ?>"
           class="w-full rounded-l-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
    <?php if (!empty($allCategories)): ?>
      <select name="category" class="mr-3">
          <option value="">All articles</option>

          <?php foreach ($allCategories as $category): ?>
              <option value=<?php
              echo $category->getTitle() ?>><?php
                echo $category->getTitle() ?></option>
          <?php endforeach; ?>

      </select>
    <?php endif; ?>
      <button type="submit"
            class="rounded-r-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
      Search
    </button>

  </div>
</form>