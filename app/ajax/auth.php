<?php
// Punto de entrada AJAX para autenticación
// Devuelve SIEMPRE JSON puro

require_once __DIR__ . '/../../Config/app.php';
require_once __DIR__ . '/../../autoload.php';
require_once __DIR__ . '/../../app/views/inc/session_start.php';
require_once __DIR__ . '/../../Config/Conexion.php';
require_once __DIR__ . '/../../Config/Core.php';

use app\controllers\Login\loginController;

header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $ctrl = new loginController();

    if ($method === 'POST') {
        $resp = $ctrl->procesar($_POST + ['action' => $_POST['action'] ?? 'login']);
        echo json_encode($resp);
        exit;
    }

    if ($method === 'GET') {
        $action = $_GET['action'] ?? 'status';
        $resp = $ctrl->procesar(['action' => $action]);
        echo json_encode($resp);
        exit;
    }

    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor', 'error' => $e->getMessage()]);
}
