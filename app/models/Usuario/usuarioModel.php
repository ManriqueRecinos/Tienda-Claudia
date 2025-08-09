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
    
}
