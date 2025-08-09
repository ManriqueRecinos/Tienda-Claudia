<?php
namespace app\models\Login;
require_once __DIR__ . '/../../../Config/Core.php';

class loginModel extends \Core
{
    public function login(string $usuario, string $contrasenia): array
    {
        $usuario = trim($usuario);
        if ($usuario === '' || $contrasenia === '') {
            return ['success' => false, 'message' => 'Usuario y contraseña son requeridos', 'user' => null];
        }

        $row = $this->getUserByUsername($usuario);
        if (!$row) {
            return ['success' => false, 'message' => 'Usuario o contraseña inválidos', 'user' => null];
        }

        if (!$this->checkPassword($contrasenia, $row['contrasenia'] ?? '')) {
            return ['success' => false, 'message' => 'Usuario o contraseña inválidos', 'user' => null];
        }

        return ['success' => true, 'message' => 'Inicio de sesión exitoso', 'user' => $row];
    }

    public function register(array $data): array
    {
        $nombres     = trim($data['nombres'] ?? '');
        $apellidos   = trim($data['apellidos'] ?? '');
        $usuario     = trim($data['usuario'] ?? '');
        $contrasenia = (string)($data['contrasenia'] ?? '');
        $rol         = $data['rol'] ?? null;
        // Normalizar rol: cuando viene como cadena vacía o 'null' desde formularios/AJAX
        if ($rol === '' || $rol === 'null') {
            $rol = null;
        }

        if ($nombres === '' || $apellidos === '' || $usuario === '' || $contrasenia === '') {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios'];
        }

        if ($this->getUserByUsername($usuario)) {
            return ['success' => false, 'message' => 'El nombre de usuario ya está en uso'];
        }

        $hash = password_hash($contrasenia, PASSWORD_BCRYPT);
        try {
            $ok = $this->insertUser([
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'usuario' => $usuario,
                'contrasenia' => $hash,
                'id_rol' => $rol,
            ]);
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'No se pudo registrar el usuario: ' . $e->getMessage()];
        }

        if (!$ok) {
            return ['success' => false, 'message' => 'No se pudo registrar el usuario'];
        }

        return ['success' => true, 'message' => 'Usuario registrado correctamente'];
    }

    private function getUserByUsername(string $usuario): ?array
    {
        $pdo = $this->conexion->getConexion();
        if (!$pdo) return null;
        $u = $pdo->quote($usuario);
        $sql = "SELECT * FROM usuarios WHERE usuario = $u";
        $row = $this->getOne($sql);
        return $row ?: null;
    }

    private function checkPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash) || $hash === $plain; // soporte temporal para contraseñas sin hash
    }

    private function insertUser(array $params): bool
    {
        $pdo = $this->conexion->getConexion();
        if (!$pdo) return false;
        $n = $pdo->quote($params['nombres'] ?? '');
        $a = $pdo->quote($params['apellidos'] ?? '');
        $u = $pdo->quote($params['usuario'] ?? '');
        $c = $pdo->quote($params['contrasenia'] ?? '');
        $rol = $params['id_rol'] ?? null;
        $hasRole = $rol !== null && $rol !== '' && $rol !== 'null';
        if ($hasRole) {
            $rolVal = (int)$rol;
            $sql = "INSERT INTO usuarios (nombres, apellidos, usuario, contrasenia, fecha_creacion, id_rol) VALUES ($n, $a, $u, $c, NOW(), $rolVal)";
        } else {
            $sql = "INSERT INTO usuarios (nombres, apellidos, usuario, contrasenia, fecha_creacion) VALUES ($n, $a, $u, $c, NOW())";
        }
        return $this->ejecutar($sql);
    }
}
?>