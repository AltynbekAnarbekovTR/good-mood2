<section class="max-w-2xl mx-auto mt-12 p-4 bg-white shadow-md border border-gray-200 rounded">
    <form method="POST" class="grid grid-cols-1 gap-6">
      <?php
      include $this->resolve('partials/_csrf.php'); ?>
        <!-- Username -->
        <label class="block">
            <span class="text-gray-700">Username</span>
            <input value="<?php echo escapeInjection($oldFormData['username'] ?? ''); ?>"
                   name="username" type="text"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                   placeholder="Alexander"/>
          <?php if (array_key_exists('username', $errors)) : ?>
              <div class="bg-gray-100 mt-2 p-2 text-red-500">
                <?php echo escapeInjection($errors['username'][0]); ?>
              </div>
          <?php endif; ?>
        </label>

        <!-- Email -->
        <label class="block">
            <span class="text-gray-700">Email address</span>
            <input value="<?php echo escapeInjection($oldFormData['email'] ?? ''); ?>"
                   name="email" type="email"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                   placeholder="john@example.com"/>
          <?php if (array_key_exists('email', $errors)) : ?>
              <div class="bg-gray-100 mt-2 p-2 text-red-500">
                <?php echo escapeInjection($errors['email'][0]); ?>
              </div>
          <?php endif; ?>
        </label>

        <!-- Password -->
        <label class="block">
            <span class="text-gray-700">Password</span>
            <input name="password" type="password"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                   placeholder=""/>
          <?php if (array_key_exists('password', $errors)) : ?>
              <div class="bg-gray-100 mt-2 p-2 text-red-500">
                <?php echo escapeInjection($errors['password'][0]); ?>
              </div>
          <?php endif; ?>
        </label>
        <!-- Confirm Password -->
        <label class="block">
            <span class="text-gray-700">Confirm Password</span>
            <input name="confirmPassword" type="password"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                   placeholder=""/>
          <?php if (array_key_exists('confirmPassword', $errors)) : ?>
              <div class="bg-gray-100 mt-2 p-2 text-red-500">
                <?php echo escapeInjection($errors['confirmPassword'][0]); ?>
              </div>
          <?php endif; ?>
        </label>
        <button type="submit" class="block w-full py-2 bg-indigo-600 text-white rounded">
            Submit
        </button>
    </form>
</section>