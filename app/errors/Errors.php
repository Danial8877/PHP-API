<?php
namespace app\errors;

class Errors
{
    static public function _403_()
    {
        http_response_code(403);
        echo json_encode([
            "status" => 403,
            "error" => "Forbidden: You don't have permission to access this resource"
        ]);
        exit;
    }

    static public function _404_()
    {
        http_response_code(404);
        echo json_encode([
            "status" => 404,
            "error" => "API Route Not Found"
        ]);
        exit;
    }
    static public function _405_()
    {
        http_response_code(405);
        echo json_encode([
            "status" => 405,
            "error" => "Method not allowed for this origin"
        ]);
        exit;
    }

    static public function _500_()
    {
        http_response_code(500);
        echo json_encode([
            "status" => 500,
            "error" => "Internal Server Error"
        ]);
        exit;
    }
}
