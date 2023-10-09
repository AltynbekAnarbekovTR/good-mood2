<section class="max-w-2xl mx-auto mt-12 p-4 bg-white shadow-md border border-gray-200 rounded">
  <form enctype="multipart/form-data" method="POST">
    <?php include $this->resolve("partials/_csrf.php"); ?>
    <label class="block">
      <span class="text-gray-700">Title <span class="light-grey">(Max: 100 symbols)</span></span>
      <input  maxlength="99" name="title" type="text"
              value="<?php echo escapeInjection($oldFormData['title'] ?? ''); ?>"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
      <?php if (isset($errors) && array_key_exists('title', $errors)) : ?>
        <div class="bg-gray-100 mt-2 p-2 text-red-500">
          <?php echo escapeInjection($errors['title'][0]); ?>
        </div>
      <?php endif; ?>
    </label>
    <label class="block">
      <span class="text-gray-700">Description <span class="light-grey">(Max: 500 symbols)</span></span>
      <input  maxlength="500" name="description" type="text"
              value="<?php echo escapeInjection($oldFormData['description'] ?? ''); ?>"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
      <?php if (isset($errors) && array_key_exists('description', $errors)) : ?>
        <div class="bg-gray-100 mt-2 p-2 text-red-500">
          <?php echo escapeInjection($errors['description'][0]); ?>
        </div>
      <?php endif; ?>
    </label>
      <label class="block">
          <span class="text-gray-700">Article text <span class="light-grey">(Max: 2000 symbols)</span></span>
          <textarea  maxlength="2000" name="text" type="text"
                     class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <?php echo escapeInjection($oldFormData['article_text'] ?? ''); ?>
          </textarea>
          <?php if (isset($errors) && array_key_exists('description', $errors)) : ?>
              <div class="bg-gray-100 mt-2 p-2 text-red-500">
                  <?php echo escapeInjection($errors['description'][0]); ?>
              </div>
          <?php endif; ?>
      </label>
      <?php foreach ($categories as $category): ?>
          <label class="mr-3"><input type="checkbox" name="category[]" value="<?php echo $category->getTitle(); ?>"><?php echo $category->getTitle(); ?></label>
      <?php endforeach; ?>
      <label class="block">
          <span class="text-gray-700">Cover Image</span>
          <input name="cover" type="file" class="block w-full text-sm text-slate-500 mt-4 file:mr-4 file:py-2 file:px-8 file:border-0 file:text-sm file:font-semibold file:bg-violet-100 file:text-violet-700 hover:file:bg-violet-200" />
          <?php if (isset($errors) && array_key_exists('cover', $errors)) : ?>
              <div class="bg-gray-100 mt-2 p-2 text-red-500">
                  <?php echo escapeInjection($errors['cover'][0]); ?>
              </div>
          <?php endif; ?>
      </label>
    <button type="submit" class="block w-full py-2 bg-indigo-600 text-white rounded">
      Submit
    </button>
  </form>
</section>