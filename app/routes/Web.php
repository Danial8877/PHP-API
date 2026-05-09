<?php

namespace app\routes;

class Web
{
    static public function CORS()
    {
        return [
            // "https://example.com" => ["GET", "POST", "PUT", "PATCH", "DELETE"]
        ];
    }
    public function routes()
    {
        Route::Get("/", "HomeController", "index", "HomeMiddleware");

        return Route::$routes;
    }
}
