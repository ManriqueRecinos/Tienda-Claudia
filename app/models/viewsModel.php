<?php
namespace app\models;

class viewsModel{
    protected function obtenerVistasModelo($vista){
        // Lista blanca de vistas: Solo se permitiran ciertas vistas
        $listaBlanca = ["dashboard", "test"];
        
        if(in_array($vista, $listaBlanca)){
            // Verificar si existe en la carpeta temática correspondiente (primera letra mayúscula)
            $vistaMayus = ucfirst($vista); // Convertir primera letra a mayúscula
            if(is_file("./app/views/contents/{$vistaMayus}/{$vista}.php")){
                $contenido = "./app/views/contents/{$vistaMayus}/{$vista}.php";
            }
            // Verificar si existe en la carpeta Dashboard
            else if(is_file("./app/views/contents/Dashboard/" . $vista . ".php")){
                $contenido = "./app/views/contents/Dashboard/" . $vista . ".php";
            } 
            // Verificar si existe en la carpeta principal
            else if(is_file("./app/views/contents/" . $vista . ".php")){
                $contenido = "./app/views/contents/" . $vista . ".php";
            }
            else{
                $contenido = "./app/views/contents/Error/404.php";
            }
        } 
        // Caso especial para login
        elseif($vista=="login"){
            if(is_file("./app/views/contents/Login/login.php")){
                $contenido = "./app/views/contents/Login/login.php";
            } else {
                $contenido = "./app/views/contents/login.php";
            }
        }
        // Caso especial para index autenticado
        elseif($vista=="index"){
            if(is_file("./app/views/contents/index.php")){
                $contenido = "./app/views/contents/index.php";
            } else if(is_file("./app/views/contents/Index/index.php")){
                $contenido = "./app/views/contents/Index/index.php";
            } else if(is_file("./app/views/contents/Dashboard/dashboard.php")){
                $contenido = "./app/views/contents/Dashboard/dashboard.php";
            } else {
                $contenido = "./app/views/contents/Error/404.php";
            }
        }
        // Caso especial para register
        elseif($vista=="register"){
            if(is_file("./app/views/contents/Register/register.php")){
                $contenido = "./app/views/contents/Register/register.php";
            } else {
                $contenido = "./app/views/contents/register.php";
            }
        }
        else{
            $contenido = "./app/views/contents/Error/404.php";
        }
        
        return $contenido;
    }
}
?>
