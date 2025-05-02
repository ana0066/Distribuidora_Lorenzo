<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['contraseña'])) { 
            $_SESSION['usuario'] = $user['nombre'];
            $_SESSION['rol'] = $user['rol'];
            $id_usuario = $user['id_usuario']; // ID del usuario obtenido de la base de datos
            $_SESSION['id_usuario'] = $id_usuario; // Guardar el ID del usuario en la sesión

            header("Location: ../index.php");
            exit();
        } else {
            echo "<p style='color: red;'>Contraseña incorrecta</p>";
        }
    } else {
        echo "<p style='color: red;'>Usuario no encontrado</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="../estilos.css">
</head>
<body>

<div class="container">

    <div class="presentacion">
        <h1>Distribuidora Lorenzo</h1>
        <p>Busca los mejores productos para la decoración y buen funcionamiento de tu hogar</p>
    </div>

    <div class="form-container">
        <img src="../img/LOGO.png" class="logo">
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <a href="register.php">¿No tienes cuenta? Regístrate</a>
    </div>

</div>

<script>
function cargarCarritoDesdeBD() {
    fetch('php/cargar_carrito.php')
        .then(res => res.json())
        .then(carrito => {
            const cartItems = document.getElementById('cart-items');
            cartItems.innerHTML = ''; // Limpiar el carrito antes de cargar los productos

            carrito.forEach(producto => {
                agregarProductoAlCarritoDOM(producto); // Mostrar cada producto en el frontend
            });

            actualizarTotal(); // Actualizar el total del carrito
        })
        .catch(error => {
            console.error('Error al cargar el carrito:', error);
        });
}

function agregarProductoAlCarritoDOM(producto) {
    const cartItems = document.getElementById('cart-items');
    const item = document.createElement('div');
    item.classList.add('cart-item');
    item.innerHTML = `
        <img src="${producto.urlImagen}" alt="${producto.nombre}">
        <div>
            <h4>${producto.nombre}</h4>
            <p>Precio: RD$${producto.valor}</p>
            <p>Cantidad: ${producto.cantidad}</p>
        </div>
    `;
    cartItems.appendChild(item);
}
</script>

</body>
</html>