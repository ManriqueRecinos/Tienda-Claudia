<?php
namespace app\models;

class viewsModel{
    protected function obtenerVistasModelo($vista){
        // Lista blanca de vistas: Solo se permitiran ciertas vistas
        $listaBlanca = ["login", "register", "dashboard", "test"];
        
        if(in_array($vista, $listaBlanca)){
            // Verificar si existe en la carpeta temática correspondiente (primera letra mayúscula)
            $vistaMayus = ucfirst($vista); // Convertir primera letra a mayúscula
            if(is_file("./app/views/contents/{$vistaMayus}/{$vista}.php")){
                return "./app/views/contents/{$vistaMayus}/{$vista}.php";
            }
            // Verificar si existe en la carpeta Dashboard
            else if(is_file("./app/views/contents/Dashboard/" . $vista . ".php")){
                return "./app/views/contents/Dashboard/" . $vista . ".php";
            } 
            // Verificar si existe en la carpeta principal
            else if(is_file("./app/views/contents/" . $vista . ".php")){
                return "./app/views/contents/" . $vista . ".php";
            }
            else{
                return "./app/views/contents/Error/404.php";
            }
        } else {
            return "./app/views/contents/Error/404.php";
        }
    }
}
?>
