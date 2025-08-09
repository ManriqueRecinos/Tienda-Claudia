<?php
namespace app\controllers\Login;

use app\models\loginModel;

class loginController extends loginModel
{
    public function procesar(array $post): array
    {
        $accion = $post['action'] ?? '';
        switch ($accion) {
            case 'login':
                return $this->handleLogin($post);
            case 'register':
                return $this->handleRegister($post);
            case 'status':
                return $this->handleStatus();
            case 'logout':
                return $this->handleLogout();
            
            default:
                return [
                    'success' => false,
                    'message' => 'Acción no válida',
                    'user'    => null
                ];
        }
    }

    private function handleLogin(array $post): array
    {
        if (!isset($_SESSION)) { session_start(); }
        $usuario     = $post['usuario'] ?? '';
        $contrasenia = $post['contrasenia'] ?? '';
        $resp = $this->login($usuario, $contrasenia);
        if ($resp['success']) {
            // Asegurar sesión segura
            if (session_status() === PHP_SESSION_ACTIVE) { session_regenerate_id(true); }
            $_SESSION['id_usuario'] = $resp['user']['id_usuario'] ?? null;
            $_SESSION['usuario']    = $resp['user']['usuario'] ?? null;
            $_SESSION['nombres']    = $resp['user']['nombres'] ?? null;
            $_SESSION['apellidos']  = $resp['user']['apellidos'] ?? null;
            $_SESSION['rol']        = $resp['user']['rol'] ?? null;
        }
        return $resp;
    }

    private function handleRegister(array $post): array
    {
        $data = [
            'nombres'     => $post['nombres'] ?? '',
            'apellidos'   => $post['apellidos'] ?? '',
            'usuario'     => $post['usuario'] ?? '',
            'contrasenia' => $post['contrasenia'] ?? '',
            'rol'         => $post['rol'] ?? null,
        ];
        if (($post['confirm_contrasenia'] ?? '') !== $data['contrasenia']) {
            return [
                'success' => false,
                'message' => 'Las contraseñas no coinciden',
                'user'    => null,
            ];
        }
        $resp = $this->register($data);
        return $resp + ['user' => null];
    }

    private function handleStatus(): array
    {
        $logged = isset($_SESSION['id_usuario']) && !empty($_SESSION['id_usuario']);
        return [
            'success' => (bool)$logged,
            'message' => $logged ? 'Sesión activa' : 'Sin sesión',
            'user'    => $logged ? [
                'id_usuario' => $_SESSION['id_usuario'] ?? null,
                'usuario'    => $_SESSION['usuario'] ?? null,
                'nombres'    => $_SESSION['nombres'] ?? null,
                'apellidos'  => $_SESSION['apellidos'] ?? null,
                'rol'        => $_SESSION['rol'] ?? null,
            ] : null
        ];
    }

    private function handleLogout(): array
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
        return [
            'success' => true,
            'message' => 'Sesión cerrada',
            'user'    => null
        ];
    }
}
?>