<?php

if (file_exists('../.env')) {
    $env = parse_ini_file('../.env');
    if ($env["APP_MODE"] === 'development') {
        require __DIR__ . '/../src/App/functions.php';
    };
}

$app = include __DIR__ . '/../src/App/bootstrap.php';

$app->run();
