<?php
namespace app\controllers\Usuario;
use app\models\Usuario\usuarioModel;

class usuarioController extends usuarioModel {
    
    public function procesar(array $post): array {
        $accion = $post['action'] ?? '';
        
        switch ($accion) {
            case 'listar':
                return $this->handleList();
            case 'obtener':
                return $this->handleGet($post);
            case 'crear':
                return $this->handleCreate($post);
            case 'actualizar':
                return $this->handleUpdate($post);
            case 'eliminar':
                return $this->handleDelete($post);
            default:
                return [
                    'success' => false,
                    'message' => 'Acción no válida'
                ];
        }
    }
    
    private function handleList(): array {
        // Verificar permisos de administrador
        if (!$this->isAdmin()) {
            return ['success' => false, 'message' => 'Sin permisos de administrador'];
        }
        
        return $this->getAllUsers();
    }
    
    private function handleGet(array $post): array {
        if (!$this->isAdmin()) {
            return ['success' => false, 'message' => 'Sin permisos de administrador'];
        }
        
        $id = $post['id'] ?? null;
        if (!$id) {
            return ['success' => false, 'message' => 'ID requerido'];
        }
        
        return $this->getUserById($id);
    }
    
    private function handleCreate(array $post): array {
        if (!$this->isAdmin()) {
            return ['success' => false, 'message' => 'Sin permisos de administrador'];
        }
        
        // Validar campos requeridos
        $required = ['nombres', 'apellidos', 'usuario', 'contrasenia'];
        foreach ($required as $field) {
            if (empty($post[$field])) {
                return ['success' => false, 'message' => "Campo {$field} es requerido"];
            }
        }
        
        // Verificar si el usuario ya existe
        if ($this->userExists($post['usuario'])) {
            return ['success' => false, 'message' => 'El nombre de usuario ya existe'];
        }
        
        // Validar confirmación de contraseña
        if ($post['contrasenia'] !== ($post['confirm_contrasenia'] ?? '')) {
            return ['success' => false, 'message' => 'Las contraseñas no coinciden'];
        }
        
        return $this->createUser($post);
    }
    
    private function handleUpdate(array $post): array {
        if (!$this->isAdmin()) {
            return ['success' => false, 'message' => 'Sin permisos de administrador'];
        }
        
        $id = $post['id'] ?? null;
        if (!$id) {
            return ['success' => false, 'message' => 'ID requerido'];
        }
        
        // Verificar si el usuario existe (para actualización)
        if (!empty($post['usuario']) && $this->userExists($post['usuario'], $id)) {
            return ['success' => false, 'message' => 'El nombre de usuario ya existe'];
        }
        
        // Validar confirmación de contraseña si se proporciona
        if (!empty($post['contrasenia']) && $post['contrasenia'] !== ($post['confirm_contrasenia'] ?? '')) {
            return ['success' => false, 'message' => 'Las contraseñas no coinciden'];
        }
        
        return $this->updateUser($id, $post);
    }
    
    private function handleDelete(array $post): array {
        if (!$this->isAdmin()) {
            return ['success' => false, 'message' => 'Sin permisos de administrador'];
        }
        
        $id = $post['id'] ?? null;
        if (!$id) {
            return ['success' => false, 'message' => 'ID requerido'];
        }
        
        // No permitir eliminar el usuario actual
        if ($id == ($_SESSION['id_usuario'] ?? null)) {
            return ['success' => false, 'message' => 'No puedes eliminar tu propio usuario'];
        }
        
        return $this->deleteUser($id);
    }
    
    private function isAdmin(): bool {
        if (!isset($_SESSION)) { session_start(); }
        return !empty($_SESSION['id_usuario']) && ($_SESSION['rol'] ?? '') == '2';
    }
}
