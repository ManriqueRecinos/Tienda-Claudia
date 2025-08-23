<?php
// Punto de entrada AJAX para autenticación
// Devuelve SIEMPRE JSON puro

require_once __DIR__ . '/../../Config/app.php';
require_once __DIR__ . '/../../autoload.php';
require_once __DIR__ . '/../views/inc/session_start.php';
require_once __DIR__ . '/../../Config/Conexion.php';
require_once __DIR__ . '/../../Config/Core.php';

use app\controllers\Login\loginController;

header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $ctrl = new loginController();

    // Endpoint de debug: /app/ajax/auth.php?action=debug (GET)
    if (($method === 'GET') && (($_GET['action'] ?? '') === 'debug')) {
        try {
            $db = new Conexion();
            echo json_encode([
                'success' => $db->isConnected(),
                'message' => $db->isConnected() ? 'Conexión OK' : 'Conexión FALLÓ',
                'debug'   => $db->getDebugInfo(),
            ]);
        } catch (Throwable $t) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al crear conexión', 'error' => $t->getMessage()]);
        }
        exit;
    }

    // Diagnóstico de esquema/tablas: /app/ajax/auth.php?action=schema (GET)
    if (($method === 'GET') && (($_GET['action'] ?? '') === 'schema')) {
        try {
            $db = new Conexion();
            $pdo = $db->getConexion();
            if (!$pdo) { echo json_encode(['success'=>false,'message'=>'Sin conexión PDO']); exit; }

            $info = [];
            $info['connected'] = $db->isConnected();
            $info['search_path'] = null;
            $info['current_schema'] = null;
            $info['table_exists'] = false;
            $info['columns'] = [];
            $info['usuarios_count'] = null;

            try { $info['search_path'] = $pdo->query("SHOW search_path")->fetchColumn(); } catch (\Throwable $e) {}
            try { $info['current_schema'] = $pdo->query("SELECT current_schema()")->fetchColumn(); } catch (\Throwable $e) {}
            try {
                $exists = $pdo->query("SELECT EXISTS (SELECT 1 FROM information_schema.tables WHERE table_schema = current_schema() AND table_name = 'usuarios')")->fetchColumn();
                $info['table_exists'] = (bool)$exists;
            } catch (\Throwable $e) {}
            if ($info['table_exists']) {
                try {
                    $cols = $pdo->query("SELECT column_name FROM information_schema.columns WHERE table_schema = current_schema() AND table_name='usuarios'")->fetchAll(PDO::FETCH_COLUMN);
                    $info['columns'] = $cols ?: [];
                } catch (\Throwable $e) {}
                try { $info['usuarios_count'] = (int)$pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn(); } catch (\Throwable $e) {}
            }

            echo json_encode(['success' => true, 'schema' => $info]);
        } catch (Throwable $t) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error de esquema', 'error' => $t->getMessage()]);
        }
        exit;
    }

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
