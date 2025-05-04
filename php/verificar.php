<?php
include 'db.php';

$mensaje = '';
$tipo = ''; // success | error

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verificar si existe un usuario con ese token
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE token = ? AND verificado = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        // Activar cuenta
        $stmtUpdate = $conn->prepare("UPDATE usuarios SET verificado = 1, token = NULL WHERE token = ?");
        $stmtUpdate->bind_param("s", $token);
        if ($stmtUpdate->execute()) {
            $mensaje = "🎉 Tu cuenta ha sido verificada con éxito. Ya puedes iniciar sesión.";
            $tipo = 'success';
        } else {
            $mensaje = "❌ Hubo un error al activar tu cuenta. Intenta más tarde.";
            $tipo = 'error';
        }
    } else {
        $mensaje = "⚠️ Token inválido, ya usado o la cuenta ya está verificada.";
        $tipo = 'error';
    }
} else {
    $mensaje = "❌ No se proporcionó ningún token.";
    $tipo = 'error';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación de cuenta</title>
    <link rel="stylesheet" href="../css/estilos.css">
    
</head>
<body>
    <div class="card">
        <h1 class="<?= $tipo ?>"><?= $tipo === 'success' ? '✅ Verificación Exitosa' : '⚠️ Verificación Fallida' ?></h1>
        <p><?= $mensaje ?></p>
        <a href="login.php">Ir al inicio de sesión</a>
    </div>
</body>
</html>
