<?php
require_once '../../Config/app.php';
require_once '../../autoload.php';
require_once '../views/inc/session_start.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    $view = $_GET['view'] ?? '';
    
    if (empty($view)) {
        echo json_encode(['success' => false, 'message' => 'Vista no especificada']);
        exit;
    }
    
    // Definir las vistas disponibles y sus rutas (rutas absolutas desde la raíz)
    $availableViews = [
        'index' => __DIR__ . '/../views/contents/Home/home.php',
        'productos' => __DIR__ . '/../views/contents/Productos/productos.php',
        'usuarios' => __DIR__ . '/../views/contents/Usuarios/usuarios.php',
        'ventas' => __DIR__ . '/../views/contents/Ventas/ventas.php'
    ];
    
    if (!array_key_exists($view, $availableViews)) {
        echo json_encode(['success' => false, 'message' => 'Vista no encontrada']);
        exit;
    }
    
    $viewPath = $availableViews[$view];
    
    if (!is_file($viewPath)) {
        echo json_encode(['success' => false, 'message' => 'Archivo de vista no encontrado']);
        exit;
    }
    
    // Variables necesarias para las vistas
    if (!isset($_SESSION)) { session_start(); }
    $isLogged = !empty($_SESSION['id_usuario']);
    $user = [
        'nombres' => $_SESSION['nombres'] ?? '',
        'apellidos' => $_SESSION['apellidos'] ?? '',
        'usuario' => $_SESSION['usuario'] ?? '',
        'rol' => $_SESSION['rol'] ?? '',
        'id_rol' => $_SESSION['id_rol'] ?? ''
    ];
    
    // Capturar el contenido de la vista
    ob_start();
    include $viewPath;
    $content = ob_get_clean();
    
    // Determinar el título de la vista
    $titles = [
        'index' => 'Inicio',
        'productos' => 'Productos',
        'usuarios' => 'Usuarios',
        'ventas' => 'Ventas'
    ];
    
    echo json_encode([
        'success' => true,
        'content' => $content,
        'title' => $titles[$view] ?? ucfirst($view),
        'view' => $view
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
