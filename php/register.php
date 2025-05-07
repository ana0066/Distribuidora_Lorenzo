<?php
session_start();
include 'db.php';
include 'enviar_correo.php'; // Función personalizada que envía el correo con cURL o Brevo

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(16)); // Token de 32 caracteres
    $rol = 'usuario';

    // Validación básica
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: red;'>Correo no válido.</p>";
        exit;
    }

    // Verificar si ya existe
    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<p style='color: red;'>⚠️ Ya existe una cuenta con ese correo.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, contraseña, rol, token, verificado) VALUES (?, ?, ?, ?, ?, 0)");
        $stmt->bind_param("sssss", $nombre, $email, $password, $rol, $token);

        if ($stmt->execute()) {
            if (enviarCorreoVerificacion($email, $nombre, $token)) {
                echo "<p style='color: green;'>✅ Registro exitoso. Revisa tu correo para verificar tu cuenta.</p>";
            } else {
                echo "<p style='color: orange;'>⚠️ Registro exitoso, pero falló el envío del correo. Contacta soporte.</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Error al registrar el usuario.</p>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    
    <div class="container">

        <div class="registro-presentacion">
            <div class="registro-nombre">Distribuidora Lorenzo</div>
            <div class="registro-slogan">Únete a nuestra comunidad, distribuyendo calidad y confianza</div>
        </div>

        <div class="form-container">
            <form method="POST" action="register.php">
                <img src="../img/LOGO.png" alt="Registro" class="img-registro">
                <h2>Registro</h2>
                <input type="text" name="nombre" placeholder="Nombre completo" required>
                <input type="email" name="email" placeholder="Correo electrónico" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">Registrarse</button>
            </form>
            <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
</body>
</html>
