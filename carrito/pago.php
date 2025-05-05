<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../php/login.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

// 1) Obtener los ítems del carrito
$sql  = "SELECT c.id, c.id_producto, p.valor, c.cantidad
         FROM carrito c
         JOIN products p ON c.id_producto = p.id
         WHERE c.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    // Carrito vacío
    header("Location: carrito.php");
    exit;
}

// 2) Calcular total
$total = 0;
$items = [];
while ($row = $res->fetch_assoc()) {
    $subtotal     = $row['valor'] * $row['cantidad'];
    $total       += $subtotal;
    $items[]      = $row;
}

// 3) Iniciar transacción
$conn->begin_transaction();

try {
    // 4) Crear pedido
    $insPedido = $conn->prepare("INSERT INTO pedidos (id_usuario, total) VALUES (?, ?)");
    $insPedido->bind_param("id", $id_usuario, $total);
    $insPedido->execute();
    $id_pedido = $insPedido->insert_id;

    // 5) Insertar detalle y descontar stock
    $insItem = $conn->prepare(
        "INSERT INTO pedido_items (id_pedido, id_producto, cantidad, precio_unitario)
         VALUES (?, ?, ?, ?)"
    );
    $updStock = $conn->prepare(
        "UPDATE products SET existencia = existencia - ? WHERE id = ? AND existencia >= ?"
    );

    foreach ($items as $item) {
        $pid      = $item['id_producto'];
        $cant     = $item['cantidad'];
        $precio   = $item['valor'];

        // Insertar línea de pedido
        $insItem->bind_param("iiid", $id_pedido, $pid, $cant, $precio);
        $insItem->execute();

        // Descontar stock
        $updStock->bind_param("iii", $cant, $pid, $cant);
        $updStock->execute();

        if ($updStock->affected_rows === 0) {
            // Stock insuficiente → error
            throw new Exception("Stock insuficiente para el producto ID $pid");
        }
    }

    // 6) Vaciar carrito
    $delCarrito = $conn->prepare("DELETE FROM carrito WHERE id_usuario = ?");
    $delCarrito->bind_param("i", $id_usuario);
    $delCarrito->execute();

    // 7) Confirmar transacción
    $conn->commit();

    // 8) Mostrar éxito
    header("Location: ../html/checkout_exito.php?pedido_id=$id_pedido");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    // Puedes registrar el error en un log y mostrar un mensaje en pantalla
    die("Error al procesar el pago: " . $e->getMessage());
}
