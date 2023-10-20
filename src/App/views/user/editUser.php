<section class="max-w-2xl mx-auto mt-12 p-4 bg-white shadow-md border border-gray-200 rounded">
  <form enctype="multipart/form-data"  method="POST">
    <?php
    include $this->resolve("partials/_csrf.php"); ?>
    <label class="block">
      <span class="text-gray-700">Username <span class="light-grey">(Max: 100 symbols)</span></span>
      <input value="<?php echo escapeInjection($user->getUsername()); ?>"
             name="username" type="text"
             class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"/>
      <?php if (array_key_exists('username', $errors)) : ?>
        <div class="bg-gray-100 mt-2 p-2 text-red-500">
          <?php echo $errors['username'][0]; ?>
        </div>
      <?php endif; ?>
    </label>
    <label class="block mt-6">
      <span class="text-gray-700">Email <span class="light-grey">(Max: 100 symbols)</span></span>
      <input value="<?php echo escapeInjection($user->getEmail()); ?>"
             name="email" type="text"
             class="mt-1  p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"/>
      <?php if (array_key_exists('email', $errors)) : ?>
        <div class="bg-gray-100 mt-2 p-2 text-red-500">
          <?php echo $errors['email'][0]; ?>
        </div>
      <?php endif; ?>
    </label>
      <?php
      foreach (['user', 'author'] as $role) {
        $checked = ($user->getRole() === $role) ? 'checked' : '';
        echo "<label class='mt-6 mr-3'>";
        echo "<input type='radio' name='role' value='$role' $checked> $role";
        echo "</label><br>";
      }
      ?>
    <button type="submit" class="block w-full  mt-6 py-2 bg-indigo-600 text-white rounded">
      Submit
    </button>
  </form>
</section>