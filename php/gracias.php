<?php
include "../menu.php";
include "db.php";

if (!isset($_SESSION['usuario_id'])) {
  header("Location: ../php/login.php");
  exit;
}
$id_usuario = $_SESSION['usuario_id'];

// Obtener detalles del pedido (productos, cantidades, precios)
$id_usuario = $_SESSION['usuario_id'] ?? 1; // Puedes usar el ID del usuario para obtener los productos comprados
$sql = "SELECT c.id, c.id_producto, p.nombre, p.valor, c.cantidad,
               (p.valor * c.cantidad) AS subtotal
        FROM carrito c
        JOIN products p ON c.id_producto = p.id
        WHERE c.id_usuario = ? AND c.estado = 'comprado'"; // 'comprado' es el estado que marcaría productos comprados
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res = $stmt->get_result();

$items = [];
while ($row = $res->fetch_assoc()) {
    $items[] = $row;
}

$total = 0;
foreach ($items as $item) {
    $total += $item['subtotal'];
}
$itbis = $total * 0.18;
$grand_total = $total + $itbis;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>¡Gracias por tu compra!</title>
  <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/gracias.css">
  
</head>
<body>

<div class="gracias-container">
  <h1>¡Gracias por tu compra! 🎉</h1>
  <p>Tu pago se ha procesado correctamente.</p>
  <p>Recibirás un correo con los detalles de tu pedido.</p>  
  <!-- Resumen del pedido -->
  <div class="recibo">
    <h3>Resumen de Compra</h3>
    <table>
      <thead>
        <tr>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item['nombre']) ?></td>
          <td><?= $item['cantidad'] ?></td>
          <td><?= number_format($item['subtotal'], 2) ?> DOP</td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <p><strong>Subtotal:</strong> <?= number_format($total, 2) ?> DOP</p>
    <p><strong>ITBIS (18%):</strong> <?= number_format($itbis, 2) ?> DOP</p>
    <p><strong>Total:</strong> <?= number_format($grand_total, 2) ?> DOP</p>
  </div>

  <!-- Botón de impresión -->
  <button onclick="window.print();">Imprimir Recibo</button>

  <p><a href="../html/productos.php">Seguir comprando</a></p>
</div>

</body>
</html>
