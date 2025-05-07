<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Buscar usuario por email
    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verificar contraseña
        if (password_verify($password, $user['contraseña'])) {

            // Si usas verificación por email:
            if (isset($user['verificado']) && !$user['verificado']) {
                $_SESSION['email_no_verificado'] = $user['email'];
                header("Location: verificar.php");
                exit();
            }

            // TODO ELIMINA CUALQUIER ASIGNACIÓN TEMPRANA. 
            // Aquí asigna las variables de sesión CORRECTAS:
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario']    = $user['nombre'];
            $_SESSION['rol']        = $user['rol'];

            header("Location: ../html/index.php");
            exit();
          } else {
            $error = "Correo o contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio de sesión</title>
  <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<div class="container">
  <div class="presentacion">
    <h1>Distribuidora Lorenzo</h1>
    <p>Busca los mejores productos para la decoración y buen funcionamiento de tu hogar</p>
  </div>

  <div class="form-container">
  <img src="../img/LOGO.png" class="logo">

  <?php if (isset($error)) : ?>
    <div class="error-message"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form action="login.php" method="POST">
    <input type="email" name="email" placeholder="Correo electrónico" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Iniciar Sesión</button>
  </form>
  <a href="register.php">¿No tienes cuenta? Regístrate</a><br>
  <a href="reenviar_verificacion.php">¿No recibiste el correo de verificación?</a>
</div>

  
</div>
</body>
</html>
