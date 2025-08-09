<?php
namespace app\models;

class viewsModel{
    protected function obtenerVistasModelo($vista){
        // Vistas que NO usan layout (login, register)
        $sinLayout = ["login", "register"];
        
        if (in_array($vista, $sinLayout)) {
            if($vista == "login"){
                return is_file("./app/views/contents/Login/login.php") 
                    ? "./app/views/contents/Login/login.php" 
                    : "./app/views/contents/Error/404.php";
            }
            elseif($vista == "register"){
                return is_file("./app/views/contents/Register/register.php") 
                    ? "./app/views/contents/Register/register.php" 
                    : "./app/views/contents/Error/404.php";
            }
        }
        
        // Vistas que SÍ usan layout (index, productos, usuarios, etc.)
        $conLayout = ["index", "productos", "usuarios", "ventas", "dashboard"];
        
        if (in_array($vista, $conLayout)) {
            // Definir el contenido específico de cada vista
            $viewContent = null;
            $currentView = $vista;
            
            if ($vista == "index") {
                $viewContent = "./app/views/contents/Home/home.php";
                $currentView = "Inicio";
            }
            elseif ($vista == "productos") {
                $viewContent = "./app/views/contents/Productos/productos.php";
                $currentView = "Productos";
            }
            elseif ($vista == "usuarios") {
                $viewContent = "./app/views/contents/Usuarios/usuarios.php";
                $currentView = "Usuarios";
            }
            elseif ($vista == "ventas") {
                $viewContent = "./app/views/contents/Ventas/ventas.php";
                $currentView = "Ventas";
            }
            else {
                $viewContent = "./app/views/contents/home.php";
                $currentView = "Panel";
            }
            
            // Verificar que el contenido existe, sino usar 404
            if (!is_file($viewContent)) {
                $viewContent = "./app/views/contents/Error/404.php";
            }
            
            // Configurar variables para el layout
            $GLOBALS['viewContent'] = $viewContent;
            $GLOBALS['currentView'] = $currentView;
            
            return "./app/views/layout/main.php";
        }
        
        // Vista no reconocida
        return "./app/views/contents/Error/404.php";
    }
}
?>
