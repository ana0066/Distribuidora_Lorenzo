<?php
session_start();
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión
echo 'logout'; // Respuesta para confirmar el logout
?>
