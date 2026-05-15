<?php

namespace app\routes;

use app\core\routes\Route;

class Web
{
    static public function CORS()
    {
        return [
            // If this array be empty => each person can send request
            // Or 
            // "https://example.com" => ["GET", "POST", "PUT", "PATCH", "DELETE"]
        ];
    }
    public function routes()
    {
        Route::Get("/", "ExampleController", "index", "ExampleMiddleware");

        return Route::$routes;
    }
}
