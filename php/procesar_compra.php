<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../php/login.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

// Obtener productos del carrito
$sql = "SELECT c.id_producto, c.cantidad, p.valor
        FROM carrito c
        JOIN products p ON c.id_producto = p.id
        WHERE c.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res = $stmt->get_result();

$productos = [];
$total = 0;

while ($row = $res->fetch_assoc()) {
    $subtotal = $row['valor'] * $row['cantidad'];
    $productos[] = [
        'id_producto' => $row['id_producto'],
        'cantidad' => $row['cantidad'],
        'precio_unitario' => $row['valor']
    ];
    $total += $subtotal;
}

if (empty($productos)) {
    echo "No hay productos en el carrito.";
    exit;
}

// Insertar venta
$stmtVenta = $conn->prepare("INSERT INTO ventas (id_usuario, total) VALUES (?, ?)");
$stmtVenta->bind_param("id", $id_usuario, $total);
$stmtVenta->execute();
$id_venta = $stmtVenta->insert_id;

// Insertar detalles
$stmtDetalle = $conn->prepare("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
foreach ($productos as $p) {
    $stmtDetalle->bind_param("iiid", $id_venta, $p['id_producto'], $p['cantidad'], $p['precio_unitario']);
    $stmtDetalle->execute();
}

// Actualizar el estado de los productos en el carrito a "comprado"
$sql = "UPDATE carrito SET estado = 'comprado' WHERE id_usuario = ? AND estado = 'pendiente'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();

// Actualizar la cantidad en la tabla products
$stmtUpdateProduct = $conn->prepare("UPDATE products SET existencia = existencia - ? WHERE id = ?");
foreach ($productos as $p) {
    $stmtUpdateProduct->bind_param("ii", $p['cantidad'], $p['id_producto']);
    $stmtUpdateProduct->execute();
}

// Vaciar el carrito después de la compra
$sqlVaciarCarrito = "DELETE FROM carrito WHERE id_usuario = ?";
$stmtVaciarCarrito = $conn->prepare($sqlVaciarCarrito);
$stmtVaciarCarrito->bind_param("i", $id_usuario);
$stmtVaciarCarrito->execute();

if ($stmtVaciarCarrito->execute()) {
    // Obtener la última venta del usuario
    $sqlVenta = "SELECT id FROM ventas WHERE id_usuario = ? ORDER BY id DESC LIMIT 1";
    $stmtVenta = $conn->prepare($sqlVenta);
    $stmtVenta->bind_param("i", $id_usuario);
    $stmtVenta->execute();
    $resVenta = $stmtVenta->get_result();

    if ($resVenta->num_rows === 0) {
        echo "No se encontró ninguna compra reciente.";
        exit;
    }

    $venta = $resVenta->fetch_assoc();
    $id_venta = $venta['id'];

    // Obtener los detalles de la venta
    $sqlDetalles = "SELECT dv.id_producto, dv.cantidad, dv.precio_unitario, p.nombre
                    FROM detalle_venta dv
                    JOIN products p ON dv.id_producto = p.id
                    WHERE dv.id_venta = ?";
    $stmtDetalles = $conn->prepare($sqlDetalles);
    $stmtDetalles->bind_param("i", $id_venta);
    $stmtDetalles->execute();
    $resDetalles = $stmtDetalles->get_result();

    $items = [];
    while ($row = $resDetalles->fetch_assoc()) {
        $items[] = $row;
    }
    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gracias por tu compra</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/gracias.css">
    </head>
    <body>
    <div class="gracias-container">

        <!-- Botón de imprimir -->
  <button class="no-imprimir" onclick="window.print();">Imprimir Recibo</button>

        <h1>¡Gracias por tu compra! 🎉</h1>
        <p>Tu pago se ha procesado correctamente.</p>
        <!-- Resumen del pedido -->
        <div class="recibo">
            <h3>Resumen de Compra</h3>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nombre']) ?></td>
                        <td><?= $item['cantidad'] ?></td>
                        <td><?= number_format($item['precio_unitario'], 2) ?> DOP</td>
                        <td><?= number_format($item['cantidad'] * $item['precio_unitario'], 2) ?> DOP</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p><a href="../html/productos.php">Seguir comprando</a></p>
    </div>
    </body>
    </html>
    <?php
    exit;
} else {
    echo "Error al procesar la compra.";
}
?>
