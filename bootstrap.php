<?php

$root = __DIR__;

$nsMap = array(
    'April\\Plugin\\' => $root . '/plugins',
    'April\\Tests\\' => $root . '/tests',
    'April\\' => $root . '/src'
);

spl_autoload_register(function($class) use ($nsMap) {
    $path = str_replace('\\', '/', $class);

    foreach ($nsMap as $ns => $prefix) {
        if (strpos($class, $ns) === 0) {
            require_once($prefix . '/'  . substr($path, strlen($ns)) . '.php');
            return;
        }
    }
});
