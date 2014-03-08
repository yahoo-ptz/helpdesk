<?php

function libs_loader($class) {
    if (ord($class[0]) == '\\') {
        $class = mb_substr($class, 1);
    }
    $class = str_replace('\\', '/', $class);

    $path = __DIR__ . '/' . $class . '.php';

    if (file_exists($path)) {
        require_once $path;
    }
}

spl_autoload_register('libs_loader');
