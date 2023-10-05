<label class="block">
  <span class="text-gray-700"><?php echo $label ?></span>
  <input name="<?php echo $name; ?>" type="<?php echo $type ?? 'text' ?>"
         value="<?php echo escapeInjection($oldFormData[$name] ?? ''); ?>"
         placeholder="<?php echo $placeholder ?? ''; ?>"
         class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"/>
  <?php if (array_key_exists($name, $errors)) : ?>
    <div class="bg-gray-100 mt-2 p-2 text-red-500">
      <?php echo escapeInjection($errors[$name][0]); ?>
    </div>
  <?php endif; ?>
</label>