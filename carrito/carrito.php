<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../php/login.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

// Obtener productos del carrito del usuario
$query = "SELECT c.id, p.nombre, p.valor, p.urlImagen, c.cantidad, (p.valor * c.cantidad) AS total
          FROM carrito c
          JOIN products p ON c.id_producto = p.id
          WHERE c.id_usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Carrito</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/carrito.css">
</head>
<body>

<main class="contenedor-carrito">
  <h2>Mi Carrito</h2>

  <?php if ($resultado->num_rows > 0): ?>
    <form method="POST" action="actualizar_carrito.php">
      <table class="tabla-carrito">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Imagen</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Total</th>
            <th>Eliminar</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $gran_total = 0;
          while ($row = $resultado->fetch_assoc()):
            $gran_total += $row['total'];
          ?>
          <tr>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td><img src="<?= htmlspecialchars($row['urlImagen']) ?>" width="50"></td>
            <td>$<?= number_format($row['valor'], 2) ?></td>
            <td>
              <input type="number" name="cantidades[<?= $row['id'] ?>]" value="<?= $row['cantidad'] ?>" min="1">
            </td>
            <td>$<?= number_format($row['total'], 2) ?></td>
            <td><a href="eliminar_carrito.php?id=<?= $row['id'] ?>">❌</a></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <div class="acciones-carrito">
        <p><strong>Total a pagar: $<?= number_format($gran_total, 2) ?></strong></p>
        <button type="submit">Actualizar cantidades</button>
        <a href="../html/checkout.php" class="boton-pagar">Proceder al pago</a>
      </div>
    </form>
  <?php else: ?>
    <p>Tu carrito está vacío.</p>
  <?php endif; ?>
</main>

</body>
</html>
