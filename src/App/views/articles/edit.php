<?php include $this->resolve("partials/_header.php"); ?>

<section class="max-w-2xl mx-auto mt-12 p-4 bg-white shadow-md border border-gray-200 rounded">
  <form method="POST" class="grid grid-cols-1 gap-6">
    <?php include $this->resolve("partials/_csrf.php"); ?>

    <label class="block">
      <span class="text-gray-700">Description</span>
      <input value="<?php echo e($article['description']); ?>" name="description" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />

      <?php if (array_key_exists('description', $errors)) : ?>
        <div class="bg-gray-100 mt-2 p-2 text-red-500">
          <?php echo $errors['description'][0]; ?>
        </div>
      <?php endif; ?>
    </label>
    <label class="block">
      <span class="text-gray-700">Title</span>
      <input value="<?php echo e($article['title']); ?>" name="title" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"/>
      <?php if (array_key_exists('title', $errors)) : ?>
        <div class="bg-gray-100 mt-2 p-2 text-red-500">
          <?php echo $errors['title'][0]; ?>
        </div>
      <?php endif; ?>
    </label>
      <label class="block">
          <span class="text-gray-700">Article text</span>
          <input value="<?php echo e($article['article_text']); ?>" name="article_text" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />

          <?php if (array_key_exists('article_text', $errors)) : ?>
              <div class="bg-gray-100 mt-2 p-2 text-red-500">
                  <?php echo $errors['article_text'][0]; ?>
              </div>
          <?php endif; ?>
      </label>
    <button type="submit" class="block w-full py-2 bg-indigo-600 text-white rounded">
      Submit
    </button>
  </form>
</section>

<?php include $this->resolve("partials/_footer.php"); ?>