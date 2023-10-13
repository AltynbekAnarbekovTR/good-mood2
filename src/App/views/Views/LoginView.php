<?php

namespace App\Views;

class LoginView extends FormView
{
  public function createLoginTemplate(array $data = [])
  {
    $loginInputs = [
            ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'placeholder' => 'john@example.com'],
            ['label' => 'Password', 'name' => 'password', 'type' => 'password'],
    ];
    $forgotPasswordLink = '<a href="/restore-password" class="forgot-password">Forgot password</a>';

    return $this->createFormTemplate($loginInputs, $forgotPasswordLink, $data);
  }
}