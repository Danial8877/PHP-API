<?php

declare(strict_types=1);

namespace app\core\auto;

function WEB()
{
    $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
    $devDomains = ['localhost', '127.0.0.1', 'dev.', 'test.', 'staging.'];

    foreach ($devDomains as $devDomain) {
        if (strpos($host, $devDomain) !== false) {
            return 'off';
        }
    }

    return 'on';
}
if (WEB() === "off") {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('log_errors', 0);
    error_reporting(E_ALL);
}
