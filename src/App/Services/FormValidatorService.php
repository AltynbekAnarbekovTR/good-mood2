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
  public array $appliedRules = [];

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
    if(!isset($this->appliedRules[$formField])) {
      $this->appliedRules[$formField] = $rules;
    }
  }

  public function removeRuleFromField(string $formField, string $rule) {
    $index = array_search($rule, $this->$formField);
    if($index !== FALSE){
      unset($this->$formField[$index]);
    }
  }

  public function validate(array $formData) {
      return $this->validator->validate($formData, $this->appliedRules);
  }
}
