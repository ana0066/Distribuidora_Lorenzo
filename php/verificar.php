<?php
session_start();
include 'db.php';
include 'enviar_correo.php';

$mensaje = '';
$tipo    = ''; // 'success' | 'error'

// 1) Proceso de verificación vía enlace (GET ?token=...)
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // ¿Existe un usuario sin verificar con ese token?
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE token = ? AND verificado = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows === 1) {
        // Activar cuenta y limpiar el token
        $upd = $conn->prepare("UPDATE usuarios SET verificado = 1, token = NULL WHERE token = ?");
        $upd->bind_param("s", $token);
        if ($upd->execute()) {
            $mensaje = "🎉 Tu cuenta ha sido verificada con éxito. Ya puedes iniciar sesión.";
            $tipo    = 'success';
            // Limpiamos la sesión de email temporal, si existe
            unset($_SESSION['email_no_verificado']);
        } else {
            $mensaje = "❌ Hubo un error al activar tu cuenta. Intenta más tarde.";
            $tipo    = 'error';
        }
    } else {
        $mensaje = "⚠️ Token inválido, ya usado o la cuenta ya está verificada.";
        $tipo    = 'error';
    }
}

// 2) Proceso de reenvío de token (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Tomamos el email guardado en sesión durante el login
    $email = $_SESSION['email_no_verificado'] ?? null;

    if ($email) {
        // Recuperamos el nombre de usuario para el saludo
        $stmt = $conn->prepare("SELECT nombre FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resUser = $stmt->get_result();
        $user    = $resUser->fetch_assoc();
        $nombre  = $user['nombre'] ?? '';

        // Generar un nuevo token seguro
        $token = bin2hex(random_bytes(16));

        // Guardar el token en BD
        $upd = $conn->prepare("UPDATE usuarios SET token = ? WHERE email = ?");
        $upd->bind_param("ss", $token, $email);

        if ($upd->execute()) {
            // Enviar correo de verificación con la API de Brevo
            if (enviarCorreoVerificacion($email, $nombre, $token)) {
                $mensaje = "✅ El enlace de verificación se ha reenviado a tu correo.";
                $tipo    = 'success';
            } else {
                $mensaje = "❌ No se pudo enviar el correo. Inténtalo más tarde.";
                $tipo    = 'error';
            }
        } else {
            $mensaje = "❌ Error al generar un nuevo token.";
            $tipo    = 'error';
        }

    } else {
        $mensaje = "❌ No se encontró un correo asociado para reenviar el token.";
        $tipo    = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Verificar Cuenta | Distribuidora Lorenzo</title>
  <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
  <div class="container">
    <div class="form-container">
      <h1>Verifica tu cuenta</h1>
      <p>Para completar tu registro necesitas verificar tu correo electrónico.</p>

      <?php if ($mensaje): ?>
        <p class="<?= $tipo === 'success' ? 'alert-success' : 'alert-error' ?>">
          <?= htmlspecialchars($mensaje) ?>
        </p>
      <?php endif; ?>

      <?php if ($tipo !== 'success'): ?>
        <!-- Formulario de reenvío solo si aún no se confirmó -->
        <form method="POST" action="verificar.php">
          <button type="submit">Reenviar enlace de verificación</button>
        </form>
      <?php endif; ?>

      <p><a href="login.php">Volver al inicio de sesión</a></p>

      <img src="../img/LOGO.png" class="logo">
    </div>
  </div>
</body>
</html>
