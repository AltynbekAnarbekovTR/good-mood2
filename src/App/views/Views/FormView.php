<?php

namespace App\Views;

class FormView extends View {
  public function createFormTemplate(array $inputsToCreate = [], string $otherFormElements = '', array $data = [])
  {
    $createdInputs = '';
    foreach ($inputsToCreate as $input) {
      $createdInputs = $createdInputs.$this->templateEngine->render('input.php', $input);
    }
    return $this->templateEngine->render('form.php', ['createdInputs' => $createdInputs, 'otherFormElements' => $otherFormElements]);
  }
}