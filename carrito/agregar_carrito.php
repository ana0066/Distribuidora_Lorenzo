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
$cantidadAgregar = isset($input['cantidad']) ? (int)$input['cantidad'] : 1;
$id_usuario = $_SESSION['id_usuario'] ?? 1; // Usa el ID del usuario logueado

// 1. Obtener la existencia del producto
$stmtStock = $conn->prepare("SELECT existencia FROM products WHERE id = ?");
$stmtStock->bind_param("i", $id_producto);
$stmtStock->execute();
$resStock = $stmtStock->get_result();

if ($resStock->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
    exit;
}

$rowStock = $resStock->fetch_assoc();
$stockDisponible = (int)$rowStock['existencia'];

// 2. Verificar si el producto ya está en el carrito
$stmt = $conn->prepare("SELECT id, cantidad FROM carrito WHERE id_usuario = ? AND id_producto = ?");
$stmt->bind_param("ii", $id_usuario, $id_producto);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    // Si ya está en el carrito, actualizar cantidad si no supera el stock
    $fila = $res->fetch_assoc();
    $nuevaCantidad = $fila['cantidad'] + $cantidadAgregar;

    if ($nuevaCantidad > $stockDisponible) {
        echo json_encode(['success' => false, 'message' => 'No hay suficiente stock']);
        exit;
    }

    $upd = $conn->prepare("UPDATE carrito SET cantidad = ? WHERE id = ?");
    $upd->bind_param("ii", $nuevaCantidad, $fila['id']);
    $upd->execute();
} else {
    // Si no está en el carrito, agregarlo si hay stock suficiente
    if ($cantidadAgregar > $stockDisponible) {
        echo json_encode(['success' => false, 'message' => 'Stock insuficiente']);
        exit;
    }

    $ins = $conn->prepare("INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (?, ?, ?)");
    $ins->bind_param("iii", $id_usuario, $id_producto, $cantidadAgregar);
    $ins->execute();
}

echo json_encode(['success' => true]);
exit;
