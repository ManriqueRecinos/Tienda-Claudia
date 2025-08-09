<?php
namespace app\controllers\Usuario;
use app\models\Usuario\usuarioModel;
require_once __DIR__ . '/../../models/Usuario/usuarioModel.php';

class usuarioController extends usuarioModel {
    
    public function procesar(array $request = []): void {
        $action = $request['action']
            ?? ($_GET['action'] ?? ($_POST['action'] ?? ''));

        switch ($action) {
            case 'obtener_usuarios':
                $data = $this->getAllUsers();
                if (!headers_sent()) {
                    header('Content-Type: application/json; charset=utf-8');
                }
                echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                exit;
                case 'obtener_usuario':
                    $id = $request['id_usuario'] ?? ($_GET['id_usuario'] ?? ($_POST['id_usuario'] ?? ''));
                    $data = $this->getUserById($id);
                    if (!headers_sent()) {
                        header('Content-Type: application/json; charset=utf-8');
                    }
                    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    exit;

            default:
                if (!headers_sent()) {
                    header('Content-Type: application/json; charset=utf-8');
                    http_response_code(400);
                }
                echo json_encode([
                    'success' => false,
                    'message' => 'Acción no válida',
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                exit;
        }
    }
}

// Permitir acceder directamente al controlador desde el navegador para pruebas
// Ejemplo: http://localhost/TiendaClaudia/app/controllers/Usuario/usuarioController.php?action=listar
if (isset($_SERVER['SCRIPT_FILENAME']) && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    $ctrl = new usuarioController();
    $ctrl->procesar($_REQUEST);
}
