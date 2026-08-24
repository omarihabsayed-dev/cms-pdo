<?php
spl_autoload_register(function($className) {
    $directory = __DIR__ . '/classes/';
    $file = $directory . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        die("Class file for '$className' not found in directory '$directory'.");
    }
});