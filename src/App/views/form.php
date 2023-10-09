<section class="max-w-2xl mx-auto mt-12 p-4 bg-white shadow-md border border-gray-200 rounded">
  <form method="POST" class="grid grid-cols-1 gap-6">
    <?php include $this->resolve('partials/_csrf.php'); ?>
    <?php echo $createdInputs;  ?>
    <?php echo $otherFormElements ?? '';  ?>
    <label class="block">
      <?php if (array_key_exists('otherLoginErrors', $errors)) : ?>
        <div class="bg-gray-100 mt-2 p-2 text-red-500">
          <?php echo escapeInjection($errors['otherLoginErrors'][0]); ?>
        </div>
      <?php endif; ?>
    </label>
    <button type="submit" class=" py-2 bg-indigo-600 text-white rounded">
      Submit
    </button>
  </form>
</section>