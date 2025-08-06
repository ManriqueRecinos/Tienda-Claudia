<!-- Aqui se ejecturara toda la aplicacion -->
<?php
require_once './Config/app.php';
require_once './Config/Conexion.php';
require_once './Config/Core.php';
require_once './Config/server.php';
require_once './autoload.php';

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