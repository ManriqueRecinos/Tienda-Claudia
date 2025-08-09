<?php
namespace app\models\Usuario;
require_once __DIR__ . '/../../../Config/Core.php';

class usuarioModel extends \Core {

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

        // Construir arreglo asociativo como 'datos' => [ ... ]
        // Cada elemento ya es asociativo: ['id_usuario' => ..., 'nombres' => ..., ...]
        // Acceso: $resultado['datos'][0]['id_usuario']
        return [
            'datos' => $rows,
            'total' => count($rows),
        ];
    }

    public function getUserById(int $id): ?array {
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
                WHERE us.id_usuario = :id";
        $params = ['id' => $id];
        $row = $this->get_all($sql, $params);
        return $row;
    }

    public function updateUser(int $id, array $data): bool {
        $sql = "UPDATE usuarios SET 
                    nombres = :nombres,
                    apellidos = :apellidos,
                    usuario = :usuario,
                    contrasenia = :contrasenia,
                    ult_modificacion = NOW(),
                    id_rol = :id_rol
                WHERE id_usuario = :id";
        $params = [
            'id' => $id,
            'nombres' => $data['nombres'],
            'apellidos' => $data['apellidos'],
            'usuario' => $data['usuario'],
            'contrasenia' => $data['contrasenia'],
            'id_rol' => $data['id_rol'],
        ];
        return $this->ejecutar($sql, $params);
    }

    public function deleteUser(int $id): bool {
        $sql = "DELETE FROM usuarios WHERE id_usuario = :id";
        $params = ['id' => $id];
        return $this->ejecutar($sql, $params);
    }
}
