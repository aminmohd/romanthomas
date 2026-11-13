<?php

/**
 * Router script for PHP built-in server (development).
 *
 *   /opt/php-8.5.12/bin/php -S localhost:8085 -t public public/router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

require_once __DIR__ . '/index.php';
