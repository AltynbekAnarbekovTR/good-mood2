<?php

namespace App\Views;

use Framework\TemplateEngine;

class View
{
  public function __construct(protected TemplateEngine $templateEngine)
  {
  }
}