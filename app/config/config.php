<?php
if (!defined('BASE_PATH')) {
    $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    define('BASE_PATH', $base === '' ? '' : $base);
}

function url(string $path = ''): string
{
    $path = ltrim($path, '/');
    return BASE_PATH . ($path !== '' ? '/' . $path : '');
}
