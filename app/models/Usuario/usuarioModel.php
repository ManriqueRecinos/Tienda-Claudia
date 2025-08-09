<?php
namespace app\models\Usuario;

class usuarioModel extends \Core {
    
    // Obtener todos los usuarios
    public function getAllUsers() {
        try {
            $sql = "SELECT id_usuario, nombres, apellidos, usuario, rol, fecha_creacion FROM usuarios ORDER BY fecha_creacion DESC";
            $stmt = $this->conectar()->prepare($sql);
            $stmt->execute();
            return [
                'success' => true,
                'data' => $stmt->fetchAll(\PDO::FETCH_ASSOC)
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al obtener usuarios: ' . $e->getMessage()
            ];
        }
    }
    
    // Obtener usuario por ID
    public function getUserById($id) {
        try {
            $sql = "SELECT id_usuario, nombres, apellidos, usuario, rol, fecha_creacion FROM usuarios WHERE id_usuario = ?";
            $stmt = $this->conectar()->prepare($sql);
            $stmt->execute([$id]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return [
                'success' => true,
                'data' => $user ?: null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al obtener usuario: ' . $e->getMessage()
            ];
        }
    }
    
    // Crear nuevo usuario
    public function createUser($data) {
        try {
            $sql = "INSERT INTO usuarios (nombres, apellidos, usuario, contrasenia, rol, fecha_creacion) VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $this->conectar()->prepare($sql);
            
            $hashedPassword = password_hash($data['contrasenia'], PASSWORD_BCRYPT);
            $rol = !empty($data['rol']) ? $data['rol'] : null;
            
            $stmt->execute([
                $data['nombres'],
                $data['apellidos'], 
                $data['usuario'],
                $hashedPassword,
                $rol
            ]);
            
            return [
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'id' => $this->conectar()->lastInsertId()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al crear usuario: ' . $e->getMessage()
            ];
        }
    }
    
    // Actualizar usuario
    public function updateUser($id, $data) {
        try {
            $fields = [];
            $values = [];
            
            if (!empty($data['nombres'])) {
                $fields[] = "nombres = ?";
                $values[] = $data['nombres'];
            }
            if (!empty($data['apellidos'])) {
                $fields[] = "apellidos = ?";
                $values[] = $data['apellidos'];
            }
            if (!empty($data['usuario'])) {
                $fields[] = "usuario = ?";
                $values[] = $data['usuario'];
            }
            if (!empty($data['contrasenia'])) {
                $fields[] = "contrasenia = ?";
                $values[] = password_hash($data['contrasenia'], PASSWORD_BCRYPT);
            }
            if (isset($data['rol'])) {
                $fields[] = "rol = ?";
                $values[] = !empty($data['rol']) ? $data['rol'] : null;
            }
            
            if (empty($fields)) {
                return ['success' => false, 'message' => 'No hay campos para actualizar'];
            }
            
            $values[] = $id;
            $sql = "UPDATE usuarios SET " . implode(', ', $fields) . " WHERE id_usuario = ?";
            $stmt = $this->conectar()->prepare($sql);
            $stmt->execute($values);
            
            return [
                'success' => true,
                'message' => 'Usuario actualizado exitosamente'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al actualizar usuario: ' . $e->getMessage()
            ];
        }
    }
    
    // Eliminar usuario
    public function deleteUser($id) {
        try {
            $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
            $stmt = $this->conectar()->prepare($sql);
            $stmt->execute([$id]);
            
            return [
                'success' => true,
                'message' => 'Usuario eliminado exitosamente'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al eliminar usuario: ' . $e->getMessage()
            ];
        }
    }
    
    // Verificar si usuario existe
    public function userExists($usuario, $excludeId = null) {
        try {
            $sql = "SELECT id_usuario FROM usuarios WHERE usuario = ?";
            $params = [$usuario];
            
            if ($excludeId) {
                $sql .= " AND id_usuario != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->conectar()->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetch() !== false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
