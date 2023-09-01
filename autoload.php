<?php

define('AUTOLOADER_INITIALIZED', '1');

$classmap = [
    'Framework' => __DIR__ . '/src/Framework',
    'Framework'.DIRECTORY_SEPARATOR.'Rules' => __DIR__ . '/src/Framework/Rules',
    'Framework'.DIRECTORY_SEPARATOR.'Exceptions' => __DIR__ . '/src/Framework/Exceptions',
    'Framework'.DIRECTORY_SEPARATOR.'Contracts' => __DIR__ . '/src/Framework/Contracts',
    'App'.DIRECTORY_SEPARATOR.'Controllers' => __DIR__ . '/src/App/Controllers',
    'App'.DIRECTORY_SEPARATOR.'Config' => __DIR__ . '/src/App/Config',
    'App'.DIRECTORY_SEPARATOR.'Middleware' => __DIR__ . '/src/App/Middleware',
    'App'.DIRECTORY_SEPARATOR.'Services' => __DIR__ . '/src/App/Services',
    'App'.DIRECTORY_SEPARATOR.'Exceptions' => __DIR__ . '/src/App/Exceptions'
];

spl_autoload_register(
    function (string $classname) use ($classmap) {
        $classname_parts = explode('\\', $classname);
        $classfile = array_pop($classname_parts) . '.php';
        $namespace = implode(DIRECTORY_SEPARATOR, $classname_parts);
        if (!array_key_exists($namespace, $classmap)) {
            return;
        }
        
        $file = $classmap[$namespace] . DIRECTORY_SEPARATOR . $classfile;
        if (!file_exists($file) && !class_exists($classname)) {
            return;
        }
        
        require_once $file;
    }
);