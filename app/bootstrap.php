<?php

/**
 * Application Bootstrap
 */

session_start();

// Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// PSR-4 Autoloader for app classes
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/';

    if (strpos($class, $prefix) === 0) {
        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
});
