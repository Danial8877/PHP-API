<?php

namespace app\controllers;

use app\core\libraries\Controller;

class ExampleController extends Controller
{
    public function index()
    {
        return Controller::returnJson([
            "success" => true,
            "message" => "Welcome to DanialMVC API",
            "version" => "2.0.0",
            "status" => "online",
            "framework" => [
                "name" => "DanialMVC",
                "author" => "Danial Jamshidi",
                "description" => "A lightning-fast, elegant MVC framework for modern PHP applications",
                "php_version" => "8.1+"
            ],
            "links" => [
                "github" => "https://github.com/DanialJamshidi",
                "website" => "https://danialjamshidi.ir",
                "documentation" => "/docs"
            ],
            "timestamp" => date('Y-m-d H:i:s'),
            "timezone" => date_default_timezone_get()
        ]);
    }
}