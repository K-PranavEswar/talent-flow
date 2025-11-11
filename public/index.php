<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/Models/Task.php';
require_once __DIR__ . '/../app/Models/Run.php';
require_once __DIR__ . '/../app/Models/Artifact.php';

$routes = require __DIR__ . '/../config/routes.php';
$method = $_SERVER['REQUEST_METHOD'];
$path = strtok($_SERVER['REQUEST_URI'], '?');
$base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$rel = '/' . trim(str_replace($base, '', $path), '/');
if ($rel === '//') $rel = '/';
$key = $method . ' ' . $rel;

if (isset($routes[$key])) {
    [$controllerName, $action] = $routes[$key];
    $controllerFile = __DIR__ . '/../app/Controllers/' . $controllerName . '.php';
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            if (method_exists($controller, $action)) {
                $controller->$action();
                exit;
            } else {
                http_response_code(404);
                $view = '404';
                include __DIR__ . '/../app/Views/layout.php';
                exit;
            }
        }
    }
}

$view = 'dashboard';
include __DIR__ . '/../app/Views/layout.php';
