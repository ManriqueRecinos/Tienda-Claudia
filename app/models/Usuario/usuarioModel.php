<?php
namespace app\models\Usuario;
require_once __DIR__ . '/../../../Config/Core.php';

// ------------------------------------------------------------
// Modelo de Usuarios: CRUD de usuarios
// - getAllUsers(): listar todos con mensaje y total
// - getUserById(): obtener uno con mensaje y datos
// - updateUser(): actualizar campos y devolver estado
// - deleteUser(): eliminar y reportar usuario eliminado
// ------------------------------------------------------------

class usuarioModel extends \Core {

    // Obtener todos los usuarios (acción: obtener_usuarios)
    // Retorna: success, message, datos[], total
    public function getAllUsers(): ?array {
        $sql = "SELECT us.id_usuario,
                    us.nombres,
                    us.apellidos,
                    us.usuario,
                    us.contrasenia,
                    us.fecha_creacion,
                    us.id_rol,
                    rol.nom_rol
                FROM usuarios us
                INNER JOIN roles rol ON us.id_rol = rol.id_rol";
        $rows = $this->get_all($sql) ?? [];
        $total = count($rows);
        return [
            'success' => true,
            'message' => $total > 0 ? ('Se obtuvieron ' . $total . ' usuarios') : 'No hay usuarios',
            'datos' => $rows,
            'total' => $total,
        ];
    }

    // Obtener un usuario por id (acción: obtener_usuario)
    // Retorna: success, message (con nombre completo), datos|null
    public function getUserById(int $id): ?array {
        $id = (int)$id;
        $sql = "SELECT us.id_usuario,
                    us.nombres,
                    us.apellidos,
                    us.usuario,
                    us.contrasenia,
                    us.fecha_creacion,
                    us.id_rol,
                    rol.nom_rol
                FROM usuarios us
                INNER JOIN roles rol ON us.id_rol = rol.id_rol
                WHERE us.id_usuario = $id";
        // Obtener una sola fila asociativa
        $row = $this->getOne($sql);
        if ($row) {
            $fullName = trim(($row['nombres'] ?? '') . ' ' . ($row['apellidos'] ?? ''));
            $userRef = $row['usuario'] ?? '';
            $label = $fullName !== '' ? "$fullName ($userRef)" : $userRef;
            return [
                'success' => true,
                'message' => 'Usuario ' . $label . ' obtenido correctamente',
                'datos' => $row,
            ];
        }
        return [
            'success' => false,
            'message' => 'Usuario no encontrado',
            'datos' => null,
        ];
    }

    // Actualizar un usuario por id (acción: actualizar_usuario)
    // Retorna: success, message, filas_afectadas
    public function updateUser(int $id, array $data): array {
        $pdo = $this->conexion->getConexion();
        if (!$pdo) {
            return [ 'success' => false, 'message' => 'Sin conexión a la BD' ];
        }
        $id = (int)$id;
        $nombres = $pdo->quote($data['nombres'] ?? '');
        $apellidos = $pdo->quote($data['apellidos'] ?? '');
        $usuario = $pdo->quote($data['usuario'] ?? '');
        $contrasenia = $pdo->quote($data['contrasenia'] ?? '');
        $id_rol = (int)($data['id_rol'] ?? 0);

        $sql = "UPDATE usuarios SET 
                    nombres = $nombres,
                    apellidos = $apellidos,
                    usuario = $usuario,
                    contrasenia = $contrasenia,
                    ult_modificacion = NOW(),
                    id_rol = $id_rol
                WHERE id_usuario = $id";

        $ok = $this->ejecutar($sql);
        if ($ok) {
            return [
                'success' => true,
                'message' => 'Usuario ' . ($data['usuario'] ?? '') . ' actualizado correctamente',
                'filas_afectadas' => $this->get_filas_afectadas(),
            ];
        }
        return [
            'success' => false,
            'message' => 'No hubo cambios',
            'filas_afectadas' => $this->get_filas_afectadas(),
        ];
    }

    // Eliminar un usuario por id (acción: eliminar_usuario)
    // Retorna: success, message (con nombre completo), filas_afectadas
    public function deleteUser(int $id): array {
        $id = (int)$id;

        // Obtener datos del usuario antes de eliminar para el mensaje
        $row = $this->getOne("SELECT usuario, nombres, apellidos FROM usuarios WHERE id_usuario = $id");
        if (!$row) {
            return [
                'success' => false,
                'message' => 'Usuario no encontrado',
                'filas_afectadas' => 0,
            ];
        }

        $ok = $this->ejecutar("DELETE FROM usuarios WHERE id_usuario = $id");
        if ($ok) {
            $fullName = trim(($row['nombres'] ?? '') . ' ' . ($row['apellidos'] ?? ''));
            $userRef = $row['usuario'] ?? '';
            $label = $fullName !== '' ? "$fullName ($userRef)" : $userRef;
            return [
                'success' => true,
                'message' => 'Usuario ' . $label . ' eliminado correctamente',
                'filas_afectadas' => $this->get_filas_afectadas(),
            ];
        }
        return [
            'success' => false,
            'message' => 'No hubo cambios',
            'filas_afectadas' => $this->get_filas_afectadas(),
        ];
    }

    // Cambiar estado de un usuario por id (acción: cambiar_estado)
    // Retorna: success, message, filas_afectadas, error
    public function cambiarEstado(int $id, $estado): array {
        $pdo = $this->conexion->getConexion();
        if (!$pdo) {
            return [ 'success' => false, 'message' => 'Sin conexión a la BD', 'filas_afectadas' => 0 ];
        }
        $id = (int)$id;

        // Normalizar estado a boolean y usar TRUE/FALSE para PostgreSQL
        $bool = filter_var($estado, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($bool === null) {
            $bool = ((string)$estado === '1');
        }
        $sqlBool = $bool ? 'TRUE' : 'FALSE';

        try {
            // UPDATE atomico que retorna el usuario afectado
            $sql = "UPDATE usuarios SET estado = $sqlBool, cambio_estado = NOW() WHERE id_usuario = $id RETURNING usuario";
            $stmt = $pdo->query($sql);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            $affected = (int)$stmt->rowCount();
            if ($result && $affected > 0) {
                return [
                    'success' => true,
                    'message' => 'Estado del usuario ' . ($result['usuario'] ?? (string)$id) . ' cambiado correctamente',
                    'filas_afectadas' => $affected,
                ];
            }
            return [
                'success' => false,
                'message' => 'Usuario no encontrado',
                'filas_afectadas' => 0,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Error al cambiar estado',
                'error' => $e->getMessage(),
            ];
        }
    }    
}
