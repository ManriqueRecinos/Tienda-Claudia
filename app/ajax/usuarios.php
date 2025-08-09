<?php
require_once '../../Config/app.php';
require_once '../../autoload.php';
require_once '../views/inc/session_start.php';

use app\controllers\Usuario\usuarioController;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    $controller = new usuarioController();
    $response = $controller->procesar($_POST);
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
