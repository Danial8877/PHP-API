<?php

namespace app\core\libraries;

use app\core\configs\Config;
use app\errors\Errors;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Controller
{
    static public function returnJson($data, $code = 200)
    {
        http_response_code($code);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($data);
        exit;
    }
    static public function getJson()
    {
        $data = file_get_contents("php://input");
        $data = json_decode($data);
        return $data ?? (object) ["No Data"];
    }
    static public function makeToken(array $data, int $time = 3600)
    {
        $key = Config::API_KEY();

        $payload = array_merge($data, [
            "iat" => time(),
            "exp" => time() + $time
        ]);

        return JWT::encode($payload, $key, 'HS256');
    }

    static public function getUserFromToken()
    {
        $headers = getallheaders();
        $auth = $headers['Authorization'] ?? '';

        if (empty($auth) || strpos($auth, "Bearer ") !== 0) {
            Errors::_403_();
        }

        $token = trim(substr($auth, 7)); // حذف "Bearer "

        try {
            return JWT::decode($token, new Key(Config::API_KEY(), 'HS256'));
        } catch (\Exception $e) {
            Errors::_403_();
        }
    }
}
