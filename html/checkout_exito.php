<?php
session_start();
if (!isset($_SESSION['usuario_id']) || !isset($_GET['pedido_id'])) {
    header("Location: ../html/productos.php");
    exit;
}
$id_pedido = (int)$_GET['pedido_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gracias por tu compra</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<main class="contenedor-checkout">
  <h2>¡Gracias por tu compra!</h2>
  <p>Tu pedido <strong>#<?= $id_pedido ?></strong> ha sido procesado exitosamente.</p>
  <a href="productos.php" class="boton">Seguir comprando</a>
</main>
</body>
</html>
