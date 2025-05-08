<?php
$servername = "fdb1030.awardspace.net";
$username = "4630827_distribuidoral";
$password = "Lorenzo19*";
$dbname = "4630827_distribuidoral";

// Crear conexión
$conn = @new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error . ". Por favor, verifica las credenciales y el servidor.");
}
?>
