<?php
$controller = $_GET['controller'] ?? 'tareas';
$action     = $_GET['action']     ?? 'index';

$file  = __DIR__ . '/controllers/' . ucfirst($controller) . 'Controller.php';
$class = ucfirst($controller) . 'Controller';

if (file_exists($file)) {
    require_once $file;
    $obj = new $class();
    if (method_exists($obj, $action)) {
        $obj->$action();
    }
} else {
    http_response_code(404);
    echo '404 - Página no encontrada';
}
