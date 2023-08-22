<?php

define('AUTOLOADER_INITIALIZED', '1');

$classmap = [
    'Framework' => __DIR__ . '/src/Framework',
    
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