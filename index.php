<?php

require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/controllers/ActividadApiController.php';
require_once __DIR__ . '/app/controllers/ReservaApiController.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($basePath !== '/' && $basePath !== '.') {
    $uri = substr($uri, strlen($basePath));
}
$uri = '/' . ltrim($uri, '/'); 

$segments = array_values(array_filter(explode('/', $uri)));

if (count($segments) === 0 || $segments[0] !== 'api') {
    send_json(['error' => 'Endpoint no encontrado'], 404);
}

$resource = $segments[1] ?? null;
$id       = isset($segments[2]) ? (int)$segments[2] : null;
$extra    = $segments[3] ?? null;

switch ($resource) {
    case 'actividades':
        $controller = new ActividadApiController();

        if ($method === 'GET' && $id === null) {
            $controller->list();
        } elseif ($method === 'GET' && $id !== null && $extra === null) {
            $controller->getOne($id);
        } elseif ($method === 'GET' && $id !== null && $extra === 'reservas') {

            $controller->getReservas($id);
        } else {
            send_json(['error' => 'Endpoint no encontrado'], 404);
        }
        break;

    case 'reservas':
        $controller = new ReservaApiController();

        if ($method === 'GET' && $id !== null) {
            $controller->getOne($id);
        } elseif ($method === 'POST' && $id === null) {
            $controller->create();
        } elseif ($method === 'PUT' && $id !== null) {
            $controller->update($id);
        } else {
            send_json(['error' => 'Endpoint no encontrado'], 404);
        }
        break;

    default:
        send_json(['error' => 'Recurso no encontrado'], 404);
}