<?php

declare(strict_types=1);

if (!defined('AUTOLOADER_INITIALIZED')) {
    require_once dirname(__DIR__) . '../../autoload.php';
}

use Framework\App;

$app = new App();

return $app;
