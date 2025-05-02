<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Verificar si el correo ya existe
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "<p class='error-message'>Este correo electrónico ya está registrado.</p>";
    } else {
        // Si el correo no existe, insertar el nuevo registro
        $query = "INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $nombre, $email, $password);

        if ($stmt->execute()) {
            $message = "<p class='success-message'>Registro exitoso. ¡Bienvenido a nuestra familia!</p>";
        } else {
            $message = "<p class='error-message'>Error en el registro. Por favor, inténtalo de nuevo.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrate</title>
    <link rel="stylesheet" href="../estilos.css">
</head>
<body>

<div class="container">
    
    <div class="presentacion">
            <h1>Distribuidora Lorenzo</h1>
            <p>Se parte de nuestra familia y compra nuestros productos</p>
    </div>

<div class="form-container">
    <?php if (isset($message)) echo $message; ?>
    <form action="register.php" method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Registrarse</button>
    </form>
    <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
</div>
</div>

</body>
</html>
