<?php
// Este archivo ya no se usa directamente.
// La vista index ahora usa Home/home.php a través del layout principal.
// Redirigir al layout principal
header("Location: " . APP_URL . "?views=index");
exit;
?>
