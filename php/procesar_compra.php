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
    // Redirigir al recibo (gracias.php)
    header("Location: gracias.php");
    exit;
} else {
    echo "Error al procesar la compra.";
}
?>
