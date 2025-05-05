<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Buscar al usuario por correo
    $stmt = $conn->prepare("SELECT id, nombre, verificado, token FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($user['verificado']) {
            echo "<p style='color: green;'>Tu cuenta ya está verificada. Puedes <a href='login.php'>iniciar sesión</a>.</p>";
            exit();
        }

        $token = $user['token'];

        if (!$token) {
            // Si no hay token, generamos uno nuevo
            $token = bin2hex(random_bytes(16));
            $update = $conn->prepare("UPDATE usuarios SET token = ? WHERE id = ?");
            $update->bind_param("si", $token, $user['id']);
            $update->execute();
        }

        $asunto = "Verifica tu correo";
        $mensaje = "Hola " . $user['nombre'] . ", haz clic en el siguiente enlace para verificar tu cuenta:\n\n";
        $mensaje .= "http://localhost/Distribuidora_Lorenzo/php/verificar.php?token=$token";
        $cabeceras = "From: no-reply@tusitio.com";

        if (mail($email, $asunto, $mensaje, $cabeceras)) {
            echo "<p style='color: green;'>Correo de verificación reenviado. Revisa tu bandeja de entrada.</p>";
        } else {
            echo "<p style='color: red;'>No se pudo enviar el correo. Asegúrate de que tu servidor permita la función mail().</p>";
        }

    } else {
        echo "<p style='color: red;'>No se encontró ninguna cuenta con ese correo.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reenviar verificación</title>
</head>
<body>
    <h2>Reenviar correo de verificación</h2>
    <form method="POST" action="reenviar_verificacion.php">
        <label>Correo electrónico:</label>
        <input type="email" name="email" required>
        <button type="submit">Reenviar</button>
    </form>
    <br>
    <a href="login.php">Volver al login</a>
</body>
</html>
