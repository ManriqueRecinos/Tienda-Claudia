<?php
// Autocarga de archivos
spl_autoload_register(function($clase){
    // Directorio base
    $archivo = __DIR__. "/" . $clase . ".php";
    $archivo = str_replace("\\", "/", $archivo); // Reemplazar \ por /

    // Verificar si el archivo existe
    if(file_exists($archivo)){
        require_once $archivo; // Incluir el archivo
    }
});
?>