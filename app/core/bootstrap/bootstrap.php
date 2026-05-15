<?php

session_start();
date_default_timezone_set("Asia/Tehran");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: no-referrer-when-downgrade");

$app = "../app/core/";
require_once $app . "auto/PHP_ENV.php";
require_once $app . "auto/PHP_ERROR.php";
require_once $app . "helpers/helpers.php";
require_once $app . "../../vendor/autoload.php";
