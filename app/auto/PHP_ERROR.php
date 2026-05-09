<?php

declare(strict_types=1);

namespace app\auto;

if ($_ENV["WEB"] === "off") {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('log_errors', 0);
    error_reporting(E_ALL);
}
