<?php
require_once '../php/db.php';
session_start();
header('Content-Type: application/json');

// Leer JSON crudo
$input = json_decode(file_get_contents('php://input'), true);

// Validar si se envió el ID del producto
if (!isset($input['id_producto'])) {
    echo json_encode(['success' => false, 'message' => 'ID no enviado']);
    exit;
}

$id_producto = (int)$input['id_producto'];
$cantidad = isset($input['cantidad']) ? (int)$input['cantidad'] : 1;
$id_usuario = $_SESSION['id_usuario'] ?? 1; // Usa el ID del usuario logueado

// Verificar si el producto ya está en el carrito
$stmt = $conn->prepare("SELECT id, cantidad FROM carrito WHERE id_usuario = ? AND id_producto = ?");
$stmt->bind_param("ii", $id_usuario, $id_producto);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    // Si el producto ya está en el carrito, actualizar la cantidad
    $fila = $res->fetch_assoc();
    $nuevaCantidad = $fila['cantidad'] + $cantidad;

    $upd = $conn->prepare("UPDATE carrito SET cantidad = ? WHERE id = ?");
    $upd->bind_param("ii", $nuevaCantidad, $fila['id']);
    $upd->execute();
} else {
    // Si el producto no está en el carrito, insertarlo
    $ins = $conn->prepare("INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (?, ?, ?)");
    $ins->bind_param("iii", $id_usuario, $id_producto, $cantidad);
    $ins->execute();
}

// Respuesta JSON de éxito
echo json_encode(['success' => true]);
exit;