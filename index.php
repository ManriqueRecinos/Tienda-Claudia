<!-- Aqui se ejecturara toda la aplicacion -->
<?php
// Configuraciones de la aplicacion
require_once './Config/app.php';
require_once './autoload.php';
require_once './app/views/inc/session_start.php';

// Configuraciones de la base de datos y el core
require_once './Config/Conexion.php';
require_once './Config/Core.php';

// Ruta de la aplicacion
if(isset($_GET['views'])){
    $url = explode("/", $_GET['views']);
}else{
    $url = ["login"];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Headers -->
    <?php include './app/views/inc/headers.php'; ?>
</head>
<body>
    <!-- Footers -->
    <?php include './app/views/inc/footers.php'; ?>
</body>
</html>