<?php
namespace app\core;
use app\configs\Config;
use app\errors\Errors;
use app\routes\Web;
class Core extends Web
{
    public function __construct()
    {
        $routes = $this->route();
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $normalizedUri = preg_replace('#/+#', '/', $requestUri);
        $normalizedUri = str_replace('../', '', $normalizedUri);
        if ($_ENV["WEB"] === "on") {
            $parsedPath = parse_url($normalizedUri, PHP_URL_PATH);
            $path = trim($parsedPath ?: '', "/");
        } elseif ($_ENV["WEB"] === "off") {
            $parsedPath = parse_url($normalizedUri, PHP_URL_PATH);
            if ($parsedPath !== false) {
                $path = trim(str_replace(Config::PROJECTNAME() . "/", "", $parsedPath), "/");
            } else {
                $path = '';
            }
        } else {
            Errors::_500_();
        }
        $method = $_SERVER['REQUEST_METHOD'];
        header("Content-Type: application/json; charset=utf-8");
        $allowedOrigins = $this->CORS();
        $all = [];
        if ($allowedOrigins !== $all) {
            $origin = $_SERVER['HTTP_ORIGIN'] ?? null;
            if (!$origin || !isset($allowedOrigins[$origin])) {
                Errors::_403_();
            }
            if (!in_array($method, $allowedOrigins[$origin])) {
                Errors::_405_();
            }
        }
        header("Access-Control-Allow-Origin: " . (isset($origin) ? $origin : "*"));
        header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        if ($method === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
        $controller = null;
        foreach ($routes[$method] ?? [] as $route => $info) {
            if (preg_match("#^$route$#", $path, $matches)) {
                array_shift($matches);
                $id = $matches[0] ?? null;
                if (isset($info['middleware']) && !empty($info['middleware'])) {
                    $middlewareClass = "app\\middlewares\\" . $info['middleware'];
                    if (class_exists($middlewareClass)) {
                        $middlewareInstance = new $middlewareClass();
                        if ($middlewareInstance->handle() === false) {
                            return;
                        }
                    }
                }
                if (class_exists($info['controller'])) {
                    $controller = new $info['controller'];
                    if (method_exists($controller, $info['method'])) {
                        $inputData = null;
                        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
                            $json = file_get_contents("php://input");
                            $inputData = json_decode($json, true);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                http_response_code(400);
                                echo json_encode(['error' => 'Invalid JSON']);
                                exit;
                            }
                        }
                        if (is_array($inputData)) {
                            $inputData = $this->sanitizeInput($inputData);
                        }
                        if ($id !== null) {
                            $id = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');
                        }
                        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
                            $controller->{$info['method']}($inputData, $id);
                        } else {
                            $controller->{$info['method']}($id);
                        }
                    } else {
                        Errors::_404_();
                    }
                } else {
                    Errors::_404_();
                }
                exit;
            }
        }
        if (!isset($controller)) {
            Errors::_404_();
        }
    }
    
    private function sanitizeInput(array $data): array
    {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeInput($value);
            } else {
                $sanitized[$key] = is_string($value) ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : $value;
            }
        }
        return $sanitized;
    }
    private function route()
    {
        $routes = new Web();
        return $routes->routes();
    }
}