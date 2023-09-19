<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Validator;
use Framework\Rules\{
  RequiredRule,
  EmailRule,
  MinRule,
  InRule,
  UrlRule,
  MatchRule,
  LengthMaxRule,
  NumericRule,
  DateFormatRule,
  PasswordRule
};

class FormValidatorService
{
  private Validator $validator;
  public array $usernameRules = [];
  public array $emailRules = [];
  public array $passwordRules = [];
  public array $confirmPasswordRules = [];
  public array $titleRules = [];
  public array $descriptionRules = [];

  public function __construct()
  {
    $this->validator = new Validator();
    $this->validator->add('required', new RequiredRule());
    $this->validator->add('email', new EmailRule());
    $this->validator->add('match', new MatchRule());
    $this->validator->add('lengthMax', new LengthMaxRule());
    $this->validator->add('dateFormat', new DateFormatRule());
    $this->validator->add('password', new PasswordRule());
  }

  public function addRulesToField(string $formField, array $rules) {
    foreach ($rules as $rule) {
      if (!in_array($rule, $this->$formField)) {
        array_push($this->$formField, $rule);
      }
    }
  }

  public function removeRuleFromField(string $formField, string $rule) {
    $index = array_search($rule, $this->$formField);
    if($index !== FALSE){
      unset($this->$formField[$index]);
    }
  }

  public function validateRegister(array $formData)
  {
    $this->validator->validate($formData, [
      'username' => $this->usernameRules,
      'email' => $this->emailRules,
      'password' => $this->passwordRules,
      'confirmPassword' => $this->confirmPasswordRules,
    ]);
  }

  public function validateLogin(array $formData)
  {
    $this->validator->validate($formData, [
      'email' => $this->emailRules,
      'password' => $this->passwordRules
    ]);
  }

  public function validateArticle(array $formData)
  {
    $this->validator->validate($formData, [
      'title' => $this->titleRules,
      'description' => $this->descriptionRules
    ]);
  }
}
